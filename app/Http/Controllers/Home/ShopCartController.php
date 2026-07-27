<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopCartController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = $request->session()->getId();
        $customerId = Auth::guard('customer')->id();

        $cart = ShoppingCart::with('tenant')->where(function($q) use ($customerId, $sessionId) {
            if ($customerId) {
                $q->where('customer_id', $customerId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->first();

        return view('home.cart', compact('cart'));
    }

    public function getCart(Request $request)
    {
        $sessionId = $request->session()->getId();
        $customerId = Auth::guard('customer')->id();

        $cart = ShoppingCart::where(function($q) use ($customerId, $sessionId) {
            if ($customerId) {
                $q->where('customer_id', $customerId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->first();

        return response()->json($cart ?: ['items' => [], 'total_amount' => 0]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $sessionId = $request->session()->getId();
        $customerId = Auth::guard('customer')->id();
        $product = Product::findOrFail($request->product_id);

        $cart = ShoppingCart::firstOrCreate(
            ['tenant_id' => $request->tenant_id, 'session_id' => $sessionId],
            ['customer_id' => $customerId, 'items' => [], 'subtotal' => 0, 'total_amount' => 0]
        );

        if ($customerId && !$cart->customer_id) {
            $cart->update(['customer_id' => $customerId]);
        }

        $items = $cart->items ?? [];
        $found = false;
        foreach ($items as &$item) {
            if ($item['product_id'] === $product->id) {
                $item['quantity'] += $request->quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->unit_price,
                'image' => $product->imageUrl(),
            ];
        }

        $cart->items = $items;
        $cart->total_amount = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);
        $cart->save();

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'tenant_id' => 'required',
        ]);

        $sessionId = $request->session()->getId();
        $customerId = Auth::guard('customer')->id();

        $cart = ShoppingCart::where('tenant_id', $request->tenant_id)
            ->where(function($q) use ($customerId, $sessionId) {
                if ($customerId) {
                    $q->where('customer_id', $customerId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->first();

        if ($cart) {
            $items = $cart->items ?? [];
            $items = array_filter($items, fn($item) => $item['product_id'] !== $request->product_id);

            $cart->items = array_values($items);
            $cart->total_amount = collect($cart->items)->sum(fn($i) => $i['price'] * $i['quantity']);
            $cart->save();
        }

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'tenant_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $sessionId = $request->session()->getId();
        $customerId = Auth::guard('customer')->id();

        $cart = ShoppingCart::where('tenant_id', $request->tenant_id)
            ->where(function($q) use ($customerId, $sessionId) {
                if ($customerId) {
                    $q->where('customer_id', $customerId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->first();

        if ($cart) {
            $items = $cart->items ?? [];
            foreach ($items as &$item) {
                if ($item['product_id'] === $request->product_id) {
                    $item['quantity'] = $request->quantity;
                    break;
                }
            }
            $cart->items = $items;
            $cart->total_amount = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);
            $cart->save();
        }

        return response()->json(['success' => true, 'cart' => $cart]);
    }
}
