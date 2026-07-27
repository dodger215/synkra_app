<x-layouts.app title="{{ $category->name }}">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:900px;margin:0 auto;">
            <div style="margin-bottom:1.5rem;">
                <a href="{{ route('product_service.categories.index') }}" style="color:var(--text-secondary);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Categories
                </a>
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <h1 style="color:var(--headings);margin:0 0 .25rem 0;">{{ $category->name }}</h1>
                        <p style="color:var(--text-secondary);margin:0;">Category details and linked products.</p>
                    </div>
                    <a href="{{ route('product_service.categories.edit', $category->id) }}"
                       style="background:var(--surface-secondary);border:1px solid var(--border);color:var(--text-primary);padding:.6rem 1.25rem;border-radius:8px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                </div>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:1.5rem;" />
            @endif

            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);margin-bottom:1.5rem;">
                <h3 style="margin:0 0 1rem;font-size:1.05rem;color:var(--headings);">Details</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                    <div>
                        <span style="display:block;font-size:.75rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:.25rem;">Name</span>
                        <strong style="color:var(--text-primary);">{{ $category->name }}</strong>
                    </div>
                    <div>
                        <span style="display:block;font-size:.75rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:.25rem;">Products</span>
                        <strong style="color:var(--text-primary);">{{ $category->products()->count() }}</strong>
                    </div>
                    <div style="grid-column:1 / -1;">
                        <span style="display:block;font-size:.75rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:.25rem;">Description</span>
                        <p style="margin:0;color:var(--text-primary);">{{ $category->description ?: 'No description provided.' }}</p>
                    </div>
                </div>
            </div>

            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;">
                <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);">
                    <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Products in this Category</h3>
                </div>
                @php $products = $category->products()->latest()->take(20)->get(); @endphp
                @if($products->isEmpty())
                    <div style="text-align:center;padding:3rem 2rem;color:var(--text-secondary);">
                        <i class="fa-solid fa-box-open" style="font-size:2rem;margin-bottom:.75rem;display:block;color:var(--text-secondary);"></i>
                        No products assigned to this category yet.
                    </div>
                @else
                    @php
                        $headers = ['Product', 'SKU', 'Status'];
                        $rows = $products->map(function ($product) {
                            return [
                                new \Illuminate\Support\HtmlString(
                                    '<a href="' . e(route('product_service.products.show', $product->id)) . '" style="color:var(--headings);font-weight:600;text-decoration:none;">' . e($product->name) . '</a>'
                                ),
                                new \Illuminate\Support\HtmlString(
                                    '<span style="font-family:monospace;color:var(--text-secondary);font-size:.9rem;">' . e($product->sku) . '</span>'
                                ),
                                $product->is_active ? 'Active' : 'Inactive',
                            ];
                        })->all();
                    @endphp
                    <div style="padding:0 1.5rem 1.5rem;">
                        <x-ui.table :headers="$headers" :rows="$rows" />
                    </div>
                @endif
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
