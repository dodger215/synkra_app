<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\ShoppingCart;
use App\Models\EcommerceOrder;
use App\Models\DeliveryOrder;
use App\Services\DeliveryService;
use App\Services\GeoapifyService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShopCheckoutController extends Controller
{
    protected $deliveryService;
    protected $geoapifyService;

    public function __construct(DeliveryService $deliveryService, GeoapifyService $geoapifyService)
    {
        $this->deliveryService = $deliveryService;
        $this->geoapifyService = $geoapifyService;
    }

    public function getPickupDistance(Request $request, Tenant $tenant)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if (!$tenant->latitude || !$tenant->longitude) {
            return response()->json(['success' => false, 'message' => 'Shop location not configured.'], 400);
        }

        $customerLocation = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        $shopLocation = [
            'latitude' => $tenant->latitude,
            'longitude' => $tenant->longitude,
        ];

        $route = $this->geoapifyService->getRoute($customerLocation, $shopLocation);

        if ($route) {
            return response()->json([
                'success' => true,
                'distance' => round($route['distance'], 2),
                'time' => round($route['time'], 0),
                'geometry' => $route['geometry'],
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Could not calculate route.'], 400);
    }

    public function getDeliveryQuote(Request $request, Tenant $tenant)
    {
        $request->validate([
            'provider' => 'required|in:bolt,yango',
            'dropoff' => 'required|array',
            'dropoff.address' => 'required|string',
            'dropoff.latitude' => 'required|numeric',
            'dropoff.longitude' => 'required|numeric',
        ]);

        $sessionId = $request->session()->getId();
        $customerId = Auth::guard('customer')->id();

        $cart = ShoppingCart::where('tenant_id', $tenant->id)
            ->where(function($q) use ($customerId, $sessionId) {
                if ($customerId) {
                    $q->where('customer_id', $customerId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->first();

        // In a real app, pickup would come from tenant settings or branch location
        $pickup = [
            'address' => $tenant->settings['location'] ?? 'Synkra Hub, Accra',
            'latitude' => 5.6037, // Default Accra lat
            'longitude' => -0.1870, // Default Accra lng
        ];

        $dropoff = $request->dropoff;
        $dropoff['latitude'] = (float) $dropoff['latitude'];
        $dropoff['longitude'] = (float) $dropoff['longitude'];

        $quote = $this->deliveryService->getQuote($request->provider, [
            'pickup' => $pickup,
            'dropoff' => $dropoff,
            'package' => ['weight' => 1] // Can be calculated from cart items
        ]);

        if ($quote) {
            // Apply Free Delivery Logic (Over 500)
            if ($cart && $cart->total_amount >= 500) {
                $quote['delivery_fee'] = 0;
            }
            return response()->json(['success' => true, 'quote' => $quote]);
        }

        return response()->json(['success' => false, 'message' => 'Could not get delivery quote.'], 400);
    }

    public function showCheckout(Request $request, Tenant $tenant)
    {
        $sessionId = $request->session()->getId();
        $customerId = Auth::guard('customer')->id();

        $cart = ShoppingCart::where('tenant_id', $tenant->id)
            ->where(function($q) use ($customerId, $sessionId) {
                if ($customerId) {
                    $q->where('customer_id', $customerId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->first();

        if (!$cart || empty($cart->items)) {
            return redirect()->route('home.shop', $tenant)->with('error', 'Your cart is empty.');
        }

        return view('home.checkout', compact('tenant', 'cart'));
    }

    public function processPayment(Request $request, Tenant $tenant)
    {
        $request->validate([
            'email' => 'required|email',
            'cart_id' => 'required|exists:shopping_carts,id',
            'delivery_type' => 'required|in:pickup,delivery',
            'delivery_provider' => 'required_if:delivery_type,delivery|nullable|in:bolt,yango',
            'delivery_fee' => 'required|numeric',
            'quote_id' => 'nullable|string',
            'shipping_details' => 'required|array',
        ]);

        if ($request->delivery_type === 'delivery') {
            return response()->json(['success' => false, 'message' => 'Delivery is currently coming soon.'], 400);
        }

        $cart = ShoppingCart::findOrFail($request->cart_id);
        $subaccount = $tenant->subaccounts()->where('is_active', true)->first();

        $totalAmount = $cart->total_amount + $request->delivery_fee;

        try {
            $response = Http::withToken(config('services.paystack.secret_key'))
                ->post('https://api.paystack.co/transaction/initialize', [
                    'email' => $request->email,
                    'amount' => $totalAmount * 100, // in pesewas
                    'callback_url' => route('home.checkout.callback', ['tenant' => $tenant]),
                    'subaccount' => $subaccount ? $subaccount->subaccount_code : null,
                    'metadata' => [
                        'tenant_id' => $tenant->id,
                        'cart_id' => $cart->id,
                        'customer_id' => Auth::guard('customer')->id(),
                        'delivery_type' => $request->delivery_type,
                        'delivery' => [
                            'provider' => $request->delivery_provider,
                            'fee' => $request->delivery_fee,
                            'quote_id' => $request->quote_id,
                            'shipping_details' => $request->shipping_details
                        ]
                    ],
                ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'authorization_url' => $response->json()['data']['authorization_url']
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Payment initialization failed.'], 400);
        } catch (\Exception $e) {
            Log::error('Paystack Checkout Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    public function callback(Request $request, Tenant $tenant)
    {
        $reference = $request->query('reference');

        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response->json()['data']['status'] === 'success') {
            $data = $response->json()['data'];
            $metadata = $data['metadata'];
            $deliveryInfo = $metadata['delivery'];

            // Create Order
            $cart = ShoppingCart::find($metadata['cart_id']);

            $order = EcommerceOrder::create([
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'customer_id' => $metadata['customer_id'],
                'customer_email' => $data['customer']['email'],
                'subtotal' => $cart->total_amount,
                'shipping_cost' => $deliveryInfo['fee'],
                'total_amount' => $data['amount'] / 100,
                'status' => 'paid',
                'payment_status' => 'paid',
                'payment_reference' => $reference,
                'delivery_type' => $metadata['delivery_type'] ?? 'pickup',
                'shipping_address' => $deliveryInfo['shipping_details'],
            ]);

            // Create Delivery Order record if it's delivery
            if (($metadata['delivery_type'] ?? 'pickup') === 'delivery') {
                $deliveryOrder = DeliveryOrder::create([
                    'ecommerce_order_id' => $order->id,
                    'tenant_id' => $tenant->id,
                    'provider' => $deliveryInfo['provider'],
                    'quote_id' => $deliveryInfo['quote_id'],
                    'fee' => $deliveryInfo['fee'],
                    'status' => 'paid',
                    'dropoff_address' => $deliveryInfo['shipping_details']['address'],
                    'dropoff_lat' => $deliveryInfo['shipping_details']['lat'],
                    'dropoff_lng' => $deliveryInfo['shipping_details']['lng'],
                ]);
            }

            // Clear cart
            if ($cart) {
                $cart->delete();
            }

            // Send SMS via Arkasel
            $phone = $deliveryInfo['shipping_details']['phone'] ?? '';
            if ($phone) {
                SmsService::send($phone, "Hello! Your order #{$order->order_number} has been received. Delivery via " . ucfirst($deliveryInfo['provider']) . " is being processed.");
            }

            return redirect()->route('home.order.tracking', ['tenant' => $tenant, 'order' => $order->id])->with('success', 'Order placed successfully!');
        }

        return redirect()->route('home.shop', $tenant)->with('error', 'Payment failed.');
    }

    public function trackOrder(Tenant $tenant, $orderId)
    {
        $order = EcommerceOrder::with('items')->findOrFail($orderId);
        $delivery = DeliveryOrder::where('ecommerce_order_id', $orderId)->first();

        return view('home.order_tracking', compact('tenant', 'order', 'delivery'));
    }

    public function confirmArrival(Request $request, Tenant $tenant, $orderId)
    {
        $order = EcommerceOrder::findOrFail($orderId);

        // Update order status
        $order->update([
            'fulfillment_status' => 'customer_arrived',
            'status' => 'customer_arrived'
        ]);

        return response()->json(['success' => true]);
    }

    public function confirmCollection(Request $request, Tenant $tenant, $orderId)
    {
        $order = EcommerceOrder::findOrFail($orderId);

        $order->update([
            'fulfillment_status' => 'collected',
            'status' => 'collected',
            'fulfilled_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
}
