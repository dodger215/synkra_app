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
        $categories = ProductCategory::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('product_service.categories.index', compact('categories'));
    }


    public function create()
    {
        return view('product_service.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = ProductCategory::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

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
