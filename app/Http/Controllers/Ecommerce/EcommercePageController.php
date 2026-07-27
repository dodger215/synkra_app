<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\EcommercePage;
use App\Models\EcommerceStore;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class EcommercePageController extends Controller
{
    public function index(EcommerceStore $store)
    {
        $this->authorizeStore($store);
        $pages = $store->pages()->orderBy('sort_order')->get();
        return view('ecommerce.pages.index', compact('store', 'pages'));
    }

    public function create(EcommerceStore $store)
    {
        $this->authorizeStore($store);
        return view('ecommerce.pages.create', compact('store'));
    }

    public function store(Request $request, EcommerceStore $store)
    {
        $this->authorizeStore($store);

        $validated = $request->validate([
            'page_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ecommerce_pages,slug',
            'page_type' => 'required|string|max:50',
        ]);

        $page = $store->pages()->create([
            'id' => Str::uuid(),
            'page_name' => $validated['page_name'],
            'slug' => $validated['slug'],
            'page_type' => $validated['page_type'],
            'content' => $this->getInitialContent($validated['page_type']),
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('ecommerce.pages.builder', [$store->id, $page->id])->with('success', 'Page created. Welcome to the builder!');
    }

    private function getInitialContent($type)
    {
        switch ($type) {
            case 'home':
                return [
                    'elements' => [
                        ['id' => 'hero_1', 'type' => 'container', 'content' => '', 'styles' => ['left' => 0, 'top' => 0, 'width' => '100%', 'padding' => '120px 40px', 'backgroundColor' => '#f8fafc', 'borderRadius' => '0px']],
                        ['id' => 'hero_title', 'type' => 'heading', 'content' => 'Welcome to Our Store', 'styles' => ['left' => 40, 'top' => 160, 'fontSize' => '64px', 'color' => '#0f172a', 'width' => '700px', 'fontWeight' => '800']],
                        ['id' => 'hero_sub', 'type' => 'text', 'content' => 'Experience the finest selection of premium products curated just for you.', 'styles' => ['left' => 40, 'top' => 320, 'fontSize' => '22px', 'color' => '#64748b', 'width' => '600px']],
                        ['id' => 'hero_btn', 'type' => 'button', 'content' => 'Shop Collection', 'styles' => ['left' => 40, 'top' => 420, 'backgroundColor' => '#f97316', 'color' => '#ffffff', 'padding' => '16px 40px', 'borderRadius' => '12px', 'fontSize' => '20px', 'fontWeight' => '700']],
                        ['id' => 'grid_title', 'type' => 'heading', 'content' => 'Featured Arrivals', 'styles' => ['left' => 40, 'top' => 650, 'fontSize' => '32px', 'color' => '#1e293b', 'fontWeight' => '800']],
                        ['id' => 'prod_grid', 'type' => 'product_grid', 'content' => ['title' => '', 'limit' => 4, 'columns' => 4], 'styles' => ['left' => 0, 'top' => 720, 'width' => '100%', 'padding' => '20px 40px']]
                    ],
                    'footer' => $this->getDefaultFooterContent()
                ];
            case 'collection':
                return [
                    'elements' => [
                        ['id' => 'col_title', 'type' => 'heading', 'content' => 'Our Collection', 'styles' => ['left' => 40, 'top' => 60, 'fontSize' => '36px', 'color' => '#1e293b', 'fontWeight' => '800']],
                        ['id' => 'col_grid', 'type' => 'product_grid', 'content' => ['title' => '', 'limit' => 12, 'columns' => 4], 'styles' => ['left' => 0, 'top' => 140, 'width' => '100%', 'padding' => '20px 40px']]
                    ],
                    'footer' => $this->getDefaultFooterContent()
                ];
            case 'product':
                return [
                    'elements' => [
                        ['id' => 'prod_show', 'type' => 'product_showcase', 'content' => ['product_id' => null, 'layout' => 'left'], 'styles' => ['left' => 40, 'top' => 40, 'width' => 'calc(100% - 80px)', 'padding' => '60px', 'backgroundColor' => '#ffffff', 'borderRadius' => '24px']]
                    ],
                    'footer' => $this->getDefaultFooterContent()
                ];
            default:
                return ['elements' => [], 'footer' => $this->getDefaultFooterContent()];
        }
    }

    private function getDefaultFooterContent()
    {
        return [
            'template' => 'standard',
            'styles' => [
                'backgroundColor' => '#1e293b',
                'color' => '#ffffff',
                'padding' => '60px 40px',
                'fontSize' => '14px',
                'minHeight' => '240px'
            ],
            'content' => [
                'aboutTitle' => 'About Our Store',
                'aboutText' => 'We are dedicated to bringing you the best products with exceptional quality and service.',
                'copyright' => '© ' . date('Y') . ' flowexa Store. All rights reserved.',
                'links' => [
                    ['label' => 'Privacy Policy', 'url' => '#'],
                    ['label' => 'Terms of Service', 'url' => '#'],
                    ['label' => 'Shipping Info', 'url' => '#']
                ]
            ]
        ];
    }

    public function builder(EcommerceStore $store, EcommercePage $page)
    {
        $this->authorizeStore($store);
        if ($page->store_id !== $store->id) abort(404);

        $products = Product::where('tenant_id', $store->tenant_id)->where('is_active', true)->limit(20)->get();
        $categories = ProductCategory::where('tenant_id', $store->tenant_id)->withCount('products')->get();

        return view('ecommerce.pages.builder', compact('store', 'page', 'products', 'categories'));
    }

    public function saveContent(Request $request, EcommerceStore $store, EcommercePage $page)
    {
        $this->authorizeStore($store);
        if ($page->store_id !== $store->id) abort(404);

        $validated = $request->validate([
            'content' => 'required|array',
        ]);

        $page->update([
            'content' => $validated['content'],
        ]);

        return response()->json(['success' => true]);
    }

    public function show(Request $request, EcommerceStore $store, EcommercePage $page)
    {
        $this->authorizeStore($store);
        if ($page->store_id !== $store->id) abort(404);

        $query = Product::where('tenant_id', $store->tenant_id)->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('unit_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('unit_price', '<=', $request->max_price);
        }

        $products = $query->limit(20)->get();
        $categories = ProductCategory::where('tenant_id', $store->tenant_id)->withCount('products')->get();
        $maxStorePrice = Product::where('tenant_id', $store->tenant_id)->max('unit_price') ?? 1000;

        return view('ecommerce.pages.show', compact('store', 'page', 'products', 'categories', 'maxStorePrice'));
    }

    public function edit(EcommerceStore $store, EcommercePage $page)
    {
        $this->authorizeStore($store);
        if ($page->store_id !== $store->id) abort(404);

        return view('ecommerce.pages.edit', compact('store', 'page'));
    }

    public function update(Request $request, EcommerceStore $store, EcommercePage $page)
    {
        $this->authorizeStore($store);
        if ($page->store_id !== $store->id) abort(404);

        $validated = $request->validate([
            'page_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ecommerce_pages,slug,' . $page->id,
            'is_published' => 'required|boolean',
        ]);

        $page->update($validated);

        return redirect()->route('ecommerce.pages.index', $store->id)->with('success', 'Page settings updated.');
    }

    public function destroy(EcommerceStore $store, EcommercePage $page)
    {
        $this->authorizeStore($store);
        if ($page->store_id !== $store->id) abort(404);

        $page->delete();

        return redirect()->route('ecommerce.pages.index', $store->id)->with('success', 'Page deleted.');
    }

    public function templates()
    {
        $path = storage_path('app/ecommerce_templates.json');
        if (!file_exists($path)) {
            return response()->json([]);
        }
        $raw = json_decode(file_get_contents($path), true) ?? [];
        // Return only metadata — not the full HTML — to keep the payload small
        $catalogue = array_map(fn($t) => [
            'id'      => $t['id'],
            'name'    => $t['name'],
            'preview' => $t['preview'] ?? '',
            'desc'    => $t['desc'] ?? '',
            'pages'   => array_keys($t['pages'] ?? []),
        ], $raw);
        return response()->json($catalogue);
    }

    public function applyTemplate(Request $request, EcommerceStore $store)
    {
        $this->authorizeStore($store);
        $request->validate(['template_id' => 'required|string']);

        $path = storage_path('app/ecommerce_templates.json');
        if (!file_exists($path)) {
            return response()->json(['error' => 'Template file not found'], 404);
        }

        $all = json_decode(file_get_contents($path), true) ?? [];
        $template = collect($all)->firstWhere('id', $request->template_id);
        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        // Delete existing pages for this store
        $store->pages()->delete();

        $pageTypeMap = [
            'home'           => ['name' => 'Home',           'type' => 'home',           'slug' => 'home',    'sort' => 1, 'published' => true],
            'products'       => ['name' => 'Products',       'type' => 'collection',     'slug' => 'products','sort' => 2, 'published' => true],
            'product_detail' => ['name' => 'Product Detail', 'type' => 'product',        'slug' => 'product', 'sort' => 3, 'published' => false],
            'cart'           => ['name' => 'Cart',           'type' => 'page',           'slug' => 'cart',    'sort' => 4, 'published' => false],
        ];

        $firstPage = null;
        foreach ($template['pages'] as $pageKey => $pageData) {
            $pageName = $pageData['name'] ?? ucfirst($pageKey);
            $meta = $pageTypeMap[$pageKey] ?? [
                'name' => $pageName, 'type' => 'page',
                'slug' => Str::slug($pageKey), 'sort' => 10, 'published' => false,
            ];

            // Replace placeholders in content
            $contentStr = json_encode($pageData);
            $contentStr = str_replace('[[store_name]]', $store->store_name, $contentStr);
            $processedData = json_decode($contentStr, true);

            $page = $store->pages()->create([
                'id'           => Str::uuid(),
                'page_name'    => $pageName,
                'slug'         => $meta['slug'],
                'page_type'    => $meta['type'],
                'is_published' => $meta['published'],
                'sort_order'   => $meta['sort'],
                'content'      => [
                    'elements'   => $processedData['elements'] ?? [],
                    'footer'     => $processedData['footer'] ?? $this->getDefaultFooterContent(),
                    'theme_css'  => $processedData['theme_css'] ?? '',
                    'template_id'=> $template['id'],
                ],
                'created_by'   => Auth::id(),
            ]);

            if (!$firstPage) $firstPage = $page;
        }

        return response()->json([
            'success'      => true,
            'redirect_url' => $firstPage
                ? route('ecommerce.pages.builder', [$store->id, $firstPage->id])
                : route('ecommerce.pages.index', $store->id),
        ]);
    }

    private function authorizeStore(EcommerceStore $store)
    {
        if ($store->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
    }
}
