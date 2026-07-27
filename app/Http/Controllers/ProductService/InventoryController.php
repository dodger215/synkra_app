<?php

namespace App\Http\Controllers\ProductService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Models\StockBalance;
use App\Models\StockLocation;
use App\Models\StockMovement;
use App\Models\StockMovementType;
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
        $products = Product::where('tenant_id', Auth::user()->tenant_id)->with('category')->get();
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
        $locations   = StockLocation::where('tenant_id', Auth::user()->tenant_id)->where('is_active', true)->get();

        $restored = session()->pull('product_create_draft', []);

        return view('product_service.inventory.create', compact('categories', 'locations', 'restored'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku'              => 'required|string|max:255|unique:products,sku',
            'barcode'          => 'nullable|string|max:255',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'category_id'      => 'nullable|exists:product_categories,id',
            'brand'            => 'nullable|string|max:255',
            'unit_type'        => 'nullable|string|max:50',
            'unit_price'       => 'nullable|numeric|min:0',
            'cost_price'       => 'nullable|numeric|min:0',
            'weight_kg'        => 'nullable|numeric|min:0',
            'dimensions'       => 'nullable|array',
            'min_stock_level'  => 'nullable|numeric|min:0',
            'max_stock_level'  => 'nullable|numeric|min:0',
            'reorder_point'    => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',
            'is_active'        => 'nullable|boolean',
            'tax_rate'         => 'nullable|numeric|min:0|max:100',
            'attributes'       => 'nullable|array',
            'images'           => 'nullable|array',
            'images.*'         => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            // "Save & import to stock" fields
            'import_to_stock'  => 'nullable|boolean',
            'stock_location_id'=> 'nullable|exists:stock_locations,id',
            'stock_quantity'   => 'nullable|numeric|min:0',
        ]);

        // Auto-generate barcode if none supplied
        $barcode = $request->barcode ?: strtoupper('BC-' . Str::random(10));

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }

        $product = Product::create([
            'tenant_id'        => Auth::user()->tenant_id,
            'sku'              => $request->sku,
            'barcode'          => $barcode,
            'name'             => $request->name,
            'description'      => $request->description,
            'category_id'      => $request->category_id,
            'brand'            => $request->brand,
            'unit_type'        => $request->unit_type,
            'unit_price'       => $request->unit_price,
            'cost_price'       => $request->cost_price,
            'weight_kg'        => $request->weight_kg,
            'dimensions'       => $request->dimensions,
            'min_stock_level'  => $request->min_stock_level,
            'max_stock_level'  => $request->max_stock_level,
            'reorder_point'    => $request->reorder_point,
            'reorder_quantity' => $request->reorder_quantity,
            'is_active'        => $request->boolean('is_active', true),
            'tax_rate'         => $request->tax_rate,
            'attributes'       => $request->attributes,
            'images'           => $imagePaths,
        ]);

        // "Save & Import to Stock" path
        if ($request->boolean('import_to_stock') && $request->stock_location_id && $request->stock_quantity > 0) {
            DB::transaction(function () use ($request, $product) {
                $tenantId = Auth::user()->tenant_id;
                $userId   = Auth::id();
                $qty      = abs($request->stock_quantity);

                $balance = StockBalance::firstOrCreate(
                    ['product_id' => $product->id, 'location_id' => $request->stock_location_id],
                    ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
                );

                $oldQty = $balance->quantity_on_hand;
                $newQty = $oldQty + $qty;

                $movementType = StockMovementType::where('name', 'receive')->first();

                StockMovement::create([
                    'tenant_id'        => $tenantId,
                    'product_id'       => $product->id,
                    'location_id'      => $request->stock_location_id,
                    'movement_type_id' => $movementType?->id,
                    'movement_type'    => 'receive',
                    'quantity'         => $qty,
                    'previous_balance' => $oldQty,
                    'new_balance'      => $newQty,
                    'notes'            => 'Initial stock import on product creation.',
                    'created_by'       => $userId,
                    'approved_by'      => $userId,
                    'approved_at'      => now(),
                ]);

                $balance->update(['quantity_on_hand' => $newQty]);
            });

            return redirect()
                ->route('product_service.products.show', $product->id)
                ->with('success', 'Product created and initial stock imported successfully.');
        }

        return redirect()
            ->route('product_service.products.show', $product->id)
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product    = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $categories = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('product_service.inventory.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $request->validate([
            'sku'              => 'required|string|max:255|unique:products,sku,' . $product->id,
            'barcode'          => 'nullable|string|max:255',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'category_id'      => 'nullable|exists:product_categories,id',
            'brand'            => 'nullable|string|max:255',
            'unit_type'        => 'nullable|string|max:50',
            'unit_price'       => 'nullable|numeric|min:0',
            'cost_price'       => 'nullable|numeric|min:0',
            'weight_kg'        => 'nullable|numeric|min:0',
            'dimensions'       => 'nullable|array',
            'min_stock_level'  => 'nullable|numeric|min:0',
            'max_stock_level'  => 'nullable|numeric|min:0',
            'reorder_point'    => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',
            'is_active'        => 'nullable|boolean',
            'tax_rate'         => 'nullable|numeric|min:0|max:100',
            'attributes'       => 'nullable|array',
            'new_images'       => 'nullable|array',
            'new_images.*'     => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'remove_images'    => 'nullable|array',
        ]);

        // Handle image removal
        $imagePaths = $product->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $removePath) {
                Storage::disk('public')->delete($removePath);
                $imagePaths = array_filter($imagePaths, fn($p) => $p !== $removePath);
            }
            $imagePaths = array_values($imagePaths);
        }

        // Handle new image uploads
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }

        $product->update([
            'sku'              => $request->sku,
            'barcode'          => $request->barcode,
            'name'             => $request->name,
            'description'      => $request->description,
            'category_id'      => $request->category_id,
            'brand'            => $request->brand,
            'unit_type'        => $request->unit_type,
            'unit_price'       => $request->unit_price,
            'cost_price'       => $request->cost_price,
            'weight_kg'        => $request->weight_kg,
            'dimensions'       => $request->dimensions,
            'min_stock_level'  => $request->min_stock_level,
            'max_stock_level'  => $request->max_stock_level,
            'reorder_point'    => $request->reorder_point,
            'reorder_quantity' => $request->reorder_quantity,
            'is_active'        => $request->boolean('is_active', $product->is_active),
            'tax_rate'         => $request->tax_rate,
            'attributes'       => $request->attributes,
            'images'           => $imagePaths,
        ]);

        return redirect()
            ->route('product_service.products.show', $product->id)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        if (!empty($product->images)) {
            foreach ($product->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $product->delete();

        return redirect()
            ->route('product_service.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
