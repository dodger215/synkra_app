<?php

namespace App\Http\Controllers\Api\ProductService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosOrder;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function orders()
    {
        $orders = PosOrder::where('tenant_id', Auth::user()->tenant_id)->latest()->paginate(20);
        return response()->json($orders);
    }

    public function showOrder($id)
    {
        $order = PosOrder::with('items.product')->where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return response()->json($order);
    }
}
