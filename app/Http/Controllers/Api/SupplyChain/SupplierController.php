<?php

namespace App\Http\Controllers\Api\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where('tenant_id', Auth::user()->tenant_id)->get();
        return response()->json($suppliers);
    }

    public function show($id)
    {
        $supplier = Supplier::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return response()->json($supplier);
    }
}
