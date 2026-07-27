<?php

namespace App\Http\Controllers\Api\ProductService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::where('tenant_id', Auth::user()->tenant_id)->latest()->paginate(50);
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'unit_price' => 'required|numeric',
        ]);

        $product = Product::create(array_merge($validated, [
            'tenant_id' => Auth::user()->tenant_id,
            'is_active' => true,
        ]));

        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $product->update($request->all());
        return response()->json($product);
    }
}
