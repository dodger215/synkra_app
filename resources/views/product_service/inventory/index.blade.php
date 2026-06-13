<x-layouts.app title="Product Inventory">
    <x-ui.grid>
        <x-slot:head>
            <meta name="description" content="Manage your product inventory.">
        </x-slot:head>

        <div class="synkra-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Products</h1>
                    <p style="color: var(--text-secondary); margin: 0;">Manage your entire product catalog, pricing, and stock limits.</p>
                </div>
                <a href="{{ route('product_service.products.create') }}" class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                    <i class="fa-solid fa-plus"></i> Add Product
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
            @endif

            <div class="synkra-card" style="background: var(--surface); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border); overflow: hidden;">
                @if($products->isEmpty())
                    <div style="text-align: center; padding: 4rem 2rem;">
                        <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem; color: var(--text-secondary);">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">No Products Found</h3>
                        <p style="color: var(--text-secondary); margin: 0 0 1.5rem 0; font-size: 0.95rem;">You haven't added any products to your inventory yet.</p>
                        <a href="{{ route('product_service.products.create') }}" class="synkra-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none;">Add Your First Product</a>
                    </div>
                @else
                    @php
                        $headers = ['Product', 'SKU', 'Price', 'Category', 'Status', 'Actions'];
                        $rows = $products->map(function ($product) {
                            $imageUrl = $product->imageUrl();
                            $imageHtml = $imageUrl
                                ? '<img src="' . e($imageUrl) . '" alt="' . e($product->name) . '" style="width:100%;height:100%;object-fit:cover;">'
                                : '<i class="fa-solid fa-image" style="color:var(--text-secondary);font-size:1rem;"></i>';

                            $brandHtml = $product->brand
                                ? '<div style="color:var(--text-secondary);font-size:.8rem;">' . e($product->brand) . '</div>'
                                : '';

                            $productCell = new \Illuminate\Support\HtmlString(
                                '<div style="display:flex;align-items:center;gap:1rem;">'
                                . '<div style="width:40px;height:40px;border-radius:8px;background:var(--surface-secondary);display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid var(--border);">' . $imageHtml . '</div>'
                                . '<div><a href="' . e(route('product_service.products.show', $product->id)) . '" style="color:var(--headings);font-weight:600;text-decoration:none;font-size:.95rem;">' . e($product->name) . '</a>' . $brandHtml . '</div>'
                                . '</div>'
                            );

                            $actions = new \Illuminate\Support\HtmlString(
                                '<div class="synkra-table-actions" style="padding-right: 3px;">'
                                . '<a href="' . e(route('product_service.products.edit', $product->id)) . '" class="synkra-table-action-btn" title="Edit Product"><i class="fa-solid fa-pen-to-square"></i></a>'
                                . '<a href="' . e(route('product_service.products.show', $product->id)) . '" class="synkra-table-action-btn" title="View Product"><i class="fa-solid fa-eye"></i></a>'
                                . '<form action="' . e(route('product_service.products.destroy', $product->id)) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this product?\');" style="margin:0;display:inline;">'
                                . csrf_field() . method_field('DELETE')
                                . '<button type="submit" class="synkra-table-action-btn" style="color:var(--danger);" title="Delete Product"><i class="fa-solid fa-trash"></i></button>'
                                . '</form></div>'
                            );

                            return [
                                $productCell,
                                $product->sku,
                                '$' . number_format($product->unit_price, 2),
                                $product->category ? $product->category->name : 'Uncategorized',
                                $product->is_active ? 'Active' : 'Inactive',
                                $actions,
                            ];
                        })->all();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />
                @endif
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
