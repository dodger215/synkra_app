<?php

namespace App\Http\Controllers\Api\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EcommerceOrder;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index($storeId)
    {
        $orders = EcommerceOrder::where('store_id', $storeId)->latest()->paginate(20);
        return response()->json($orders);
    }

    public function show($storeId, $orderId)
    {
        $order = EcommerceOrder::with('items.product')->where('store_id', $storeId)->findOrFail($orderId);
        return response()->json($order);
    }

    public function updateStatus(Request $request, $storeId, $orderId)
    {
        $request->validate(['status' => 'required|string']);
        $order = EcommerceOrder::where('store_id', $storeId)->findOrFail($orderId);
        $order->update(['status' => $request->status]);
        return response()->json($order);
    }
}
