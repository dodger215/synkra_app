<?php

namespace App\Http\Controllers\ProductService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductCategory;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Product;

class InventoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(CheckPermission::class . ':product_service,view', only: ['index', 'show']),
            new Middleware(CheckPermission::class . ':product_service,create', only: ['create', 'store']),
            new Middleware(CheckPermission::class . ':product_service,update', only: ['edit', 'update']),
            new Middleware(CheckPermission::class . ':product_service,delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $products = Product::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('product_service.inventory.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return view('product_service.inventory.show', compact('product'));
    }

    public function create()
    {
        $categories = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('product_service.inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:products,sku',
            'barcode' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand' => 'nullable|string|max:255',
            'unit_type' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'weight_kg' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'min_stock_level' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'reorder_point' => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'attributes' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        $product = Product::create([
            'tenant_id' => Auth::user()->tenant_id,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand' => $request->brand,
            'unit_type' => $request->unit_type,
            'unit_price' => $request->unit_price,
            'cost_price' => $request->cost_price,
            'weight_kg' => $request->weight_kg,
            'dimensions' => $request->dimensions,
            'min_stock_level' => $request->min_stock_level,
            'max_stock_level' => $request->max_stock_level,
            'reorder_point' => $request->reorder_point,
            'reorder_quantity' => $request->reorder_quantity,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
            'tax_rate' => $request->tax_rate,
            'attributes' => $request->attributes,
            'images' => $imagePaths,
        ]);

        return redirect()->route('product_service.inventory.show', $product->id)->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $categories = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('product_service.inventory.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $request->validate([
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand' => 'nullable|string|max:255',
            'unit_type' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'weight_kg' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'min_stock_level' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'reorder_point' => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'attributes' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePaths = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        $product->update([
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand' => $request->brand,
            'unit_type' => $request->unit_type,
            'unit_price' => $request->unit_price,
            'cost_price' => $request->cost_price,
            'weight_kg' => $request->weight_kg,
            'dimensions' => $request->dimensions,
            'min_stock_level' => $request->min_stock_level,
            'max_stock_level' => $request->max_stock_level,
            'reorder_point' => $request->reorder_point,
            'reorder_quantity' => $request->reorder_quantity,
            'is_active' => $request->has('is_active') ? $request->is_active : $product->is_active,
            'tax_rate' => $request->tax_rate,
            'attributes' => $request->attributes,
            'images' => $imagePaths,
        ]);

        return redirect()->route('product_service.inventory.show', $product->id)->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        
        // Optionally delete images from storage
        if (!empty($product->images)) {
            foreach ($product->images as $imagePath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('product_service.inventory.index')->with('success', 'Product deleted successfully.');
    }
}
