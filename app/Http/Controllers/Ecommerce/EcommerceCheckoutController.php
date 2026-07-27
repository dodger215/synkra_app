<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\EcommerceOrder;
use App\Models\EcommerceOrderItem;
use App\Models\EcommerceStore;
use App\Models\Product;
use App\Notifications\OrderStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EcommerceCheckoutController extends Controller
{
    public function process(Request $request, EcommerceStore $store)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $customer = Auth::guard('customer')->user();

        return DB::transaction(function () use ($request, $store, $validated, $customer) {
            $totalAmount = 0;
            $orderItems = [];

            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['id']);
                $subtotal = $product->unit_price * $itemData['qty'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'id' => Str::uuid(),
                    'product_id' => $product->id,
                    'quantity' => $itemData['qty'],
                    'unit_price' => $product->unit_price,
                    'subtotal' => $subtotal,
                ];
            }

            $order = $store->orders()->create([
                'id' => Str::uuid(),
                'tenant_id' => $store->tenant_id,
                'customer_id' => $customer ? $customer->id : null,
                'customer_email' => $validated['email'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'fulfillment_status' => 'unfulfilled',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Send notification
            if ($customer) {
                $customer->notify(new OrderStatusChanged($order));
            }

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order placed successfully!'
            ]);
        });
    }
}
