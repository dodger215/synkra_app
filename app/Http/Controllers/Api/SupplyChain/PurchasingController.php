<?php

namespace App\Http\Controllers\Api\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class PurchasingController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('supplier')->where('tenant_id', Auth::user()->tenant_id)->latest()->paginate(20);
        return response()->json($orders);
    }

    public function show($id)
    {
        $order = PurchaseOrder::with(['supplier', 'items.product'])->where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return response()->json($order);
    }
}
