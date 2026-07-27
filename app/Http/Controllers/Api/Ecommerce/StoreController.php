<?php

namespace App\Http\Controllers\Api\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EcommerceStore;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        $stores = EcommerceStore::where('tenant_id', Auth::user()->tenant_id)->get();
        return response()->json($stores);
    }

    public function show($id)
    {
        $store = EcommerceStore::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return response()->json($store);
    }
}
