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

class CategoriesController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(CheckPermission::class . ':product_service,view', only: ['index', 'show']),
            new Middleware(CheckPermission::class . ':product_service,create', only: ['store']),
            new Middleware(CheckPermission::class . ':product_service,update', only: ['update']),
            new Middleware(CheckPermission::class . ':product_service,delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $categories = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->withCount('products')->get();
        return view('product_service.categories.index', compact('categories'));
    }


    public function create()
    {
        $fromProduct = request()->boolean('from_product');
        return view('product_service.categories.create', compact('fromProduct'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = ProductCategory::create([
            'tenant_id'   => Auth::user()->tenant_id,
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => "Category '{$category->name}' created successfully.",
                'category' => ['id' => $category->id, 'name' => $category->name],
            ], 201);
        }

        // If the user came from the product create form (quick-create flow),
        // put the draft back into session and redirect to product create.
        if ($request->boolean('from_product')) {
            session()->flash('category_created', $category->name);
            session()->put('product_create_draft', $request->session()->get('product_create_draft', []));
            // Restore the selected category on return
            $draft = session()->get('product_create_draft', []);
            $draft['category_id'] = $category->id;
            session()->put('product_create_draft', $draft);
            return redirect()->route('product_service.products.create')
                ->with('success', "Category '{$category->name}' created! Your product draft has been restored.");
        }

        return redirect()->route('product_service.categories.show', $category->id, 201);
    }

    public function show($id)
    {
        $category = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return view('product_service.categories.show', compact('category'));
    }


    public function edit($id)
    {
        $category = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return view('product_service.categories.edit', compact('category'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('product_service.categories.show', $category->id, 200);
    }

    public function destroy($id)
    {
        $category = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $category->delete();

        return redirect()->route('product_service.categories.index', 204);
    }
}
