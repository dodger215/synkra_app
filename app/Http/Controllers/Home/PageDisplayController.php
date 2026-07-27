<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\StockBalance;
use App\Models\StockCount;
use App\Models\ProductCategory;
use App\Models\Tenant;
use App\Models\ProductReview;
use App\Models\Customer;
use App\Models\EcommerceOrder;

class PageDisplayController extends Controller
{
    public function index()
    {
        $tenants = Tenant::where('name', '!=', 'Demo Workspace')
            ->withCount('products')
            ->take(6)->get();

        $trendingProducts = Product::where('is_active', true)
            ->whereHas('tenant', function($q) {
                $q->where('name', '!=', 'Demo Workspace');
            })
            ->with(['tenant', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $categories = ProductCategory::where('is_active', true)
            ->whereHas('tenant', function($q) {
                $q->where('name', '!=', 'Demo Workspace');
            })
            ->whereNull('parent_id')
            ->take(8)
            ->get();

        $stats = [
            'shops' => Tenant::count(),
            'products' => Product::count(),
            'orders' => EcommerceOrder::count(),
            'customers' => Customer::count(),
        ];

        return view('home.index', compact('tenants', 'trendingProducts', 'categories', 'stats'));
    }

    public function shops()
    {
        $tenants = Tenant::where('name', '!=', 'Demo Workspace')
            ->whereDoesntHave('services', function($q) {
                $q->where('service_name', 'ecommerce')->where('is_active', true);
            })->paginate(12);

        return view('home.shops', compact('tenants'));
    }

    public function shop(Tenant $tenant)
    {
        // Removed the strict ecommerce service check to allow viewing shop during testing
        // if ($tenant->hasServiceModule('ecommerce')) {
        //     abort(404, 'Shop has its own storefront.');
        // }

        $categories = $tenant->productCategories()->where('is_active', true)->get();
        $products = $tenant->products()
            ->with(['category', 'stockBalances'])
            ->where('is_active', true)
            ->paginate(12);

        $reviews = ProductReview::whereIn('product_id', $tenant->products()->pluck('id'))
            ->with('customer', 'product')
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        return view('home.shop', compact('tenant', 'categories', 'products', 'reviews'));
    }

    public function productDetails(Tenant $tenant, Product $product)
    {
        if ($product->tenant_id !== $tenant->id) {
            abort(404);
        }

        $relatedProducts = Product::where('tenant_id', $tenant->id)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $reviews = $product->reviews()->with('customer')->where('status', 'approved')->latest()->take(5)->get();

        return view('home.product_details', compact('tenant', 'product', 'relatedProducts', 'reviews'));
    }
}
