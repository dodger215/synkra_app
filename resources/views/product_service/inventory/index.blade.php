<x-layouts.app title="Product Inventory">
    <x-ui.grid>
        <x-slot:head>
            <meta name="description" content="Manage your product inventory.">
        </x-slot:head>

        <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Products</h1>
                    <p style="color: var(--text-secondary); margin: 0;">Manage your entire product catalog, pricing, and stock limits.</p>
                </div>
                <a href="{{ route('product_service.products.create') }}" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i> Add Product
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 1.5rem;" />
            @endif
            @if($errors->any())
                <x-ui.alert type="danger" title="Error" :message="$errors->first()" style="margin-bottom: 1.5rem;" />
            @endif

            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border); overflow: hidden; padding: 1.5rem;">
                @if($products->isEmpty())
                    <div style="text-align: center; padding: 4rem 2rem;">
                        <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem; color: var(--text-secondary);">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">No Products Found</h3>
                        <p style="color: var(--text-secondary); margin: 0 0 1.5rem 0; font-size: 0.95rem;">You haven't added any products yet.</p>
                        <a href="{{ route('product_service.products.create') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none;">Add Your First Product</a>
                    </div>
                @else
                    {{-- Filter Bar --}}
                    @php
                        $categoryOptions = [['value' => '', 'label' => 'All Categories']];
                        foreach($products->pluck('category')->unique('id')->filter() as $cat) {
                            $categoryOptions[] = ['value' => $cat->name, 'label' => $cat->name];
                        }
                    @endphp
                    <x-ui.filter-bar
                        searchPlaceholder="Search products by name, SKU, brand…"
                        :filters="[
                            ['name' => 'category', 'label' => 'Category', 'options' => $categoryOptions],
                            ['name' => 'status', 'label' => 'Status', 'options' => [
                                ['value' => '', 'label' => 'All Status'],
                                ['value' => 'active', 'label' => 'Active'],
                                ['value' => 'inactive', 'label' => 'Inactive'],
                            ]],
                        ]"
                    >
                        <button type="button" class="flowexa-filter-btn" onclick="openflowexaModal('importProductsModal')">
                            <i class="fa-solid fa-file-import"></i> Import
                        </button>
                        <button type="button" class="flowexa-filter-btn" onclick="openflowexaModal('exportProductsModal')">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                    </x-ui.filter-bar>

                    @php
                        $headers = ['Product', 'SKU', 'Price', 'Category', 'Status', 'Actions'];
                        $rows = $products->map(function ($product) {
                            $imageUrl = $product->imageUrl();
                            $imageHtml = $imageUrl
                                ? '<img src="' . e($imageUrl) . '" alt="' . e($product->name) . '" style="width:100%;height:100%;object-fit:cover;">'
                                : '<i class="fa-solid fa-image" style="color:var(--text-secondary);font-size:1rem;"></i>';
                            $brandHtml = $product->brand
                                ? '<div style="color:var(--text-secondary);font-size:.8rem;">' . e($product->brand) . '</div>' : '';
                            $productCell = new \Illuminate\Support\HtmlString(
                                '<div style="display:flex;align-items:center;gap:1rem;">'
                                . '<div style="width:40px;height:40px;border-radius:8px;background:var(--surface-secondary);display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid var(--border);">' . $imageHtml . '</div>'
                                . '<div><a href="' . e(route('product_service.products.show', $product->id)) . '" style="color:var(--headings);font-weight:600;text-decoration:none;font-size:.95rem;">' . e($product->name) . '</a>' . $brandHtml . '</div>'
                                . '</div>'
                            );
                            $actions = new \Illuminate\Support\HtmlString(
                                '<div class="flowexa-table-actions" style="padding-right:3px;">'
                                . '<a href="' . e(route('product_service.products.edit', $product->id)) . '" class="flowexa-table-action-btn" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>'
                                . '<a href="' . e(route('product_service.products.show', $product->id)) . '" class="flowexa-table-action-btn" title="View"><i class="fa-solid fa-eye"></i></a>'
                                . '<form action="' . e(route('product_service.products.destroy', $product->id)) . '" method="POST" onsubmit="return confirm(\'Delete this product?\');" style="margin:0;display:inline;">'
                                . csrf_field() . method_field('DELETE')
                                . '<button type="submit" class="flowexa-table-action-btn" style="color:var(--danger);" title="Delete"><i class="fa-solid fa-trash"></i></button>'
                                . '</form></div>'
                            );
                            return [
                                $productCell,
                                $product->sku,
                                'GH₵ ' . number_format($product->unit_price, 2),
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

        {{-- Hide the auto-generated trigger buttons --}}
        <style>
            #importProductsModal-trigger-btn,
            #exportProductsModal-trigger-btn { display: none !important; }
        </style>

        {{-- ═══════ IMPORT PRODUCTS MODAL ═══════ --}}
        <x-ui.modal id="importProductsModal" triggerId="importProductsModal-trigger-btn" title="Import Products">
            <div style="background:var(--surface-secondary);border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:1.25rem;">
                <h4 style="margin:0 0 .5rem;color:var(--headings);font-size:.95rem;">
                    <i class="fa-solid fa-circle-info" style="color:var(--primary);margin-right:.4rem;"></i> File Format Instructions
                </h4>
                <ul style="margin:0;padding-left:1.25rem;color:var(--text-secondary);font-size:.85rem;line-height:1.7;">
                    <li>Supported formats: <strong>.xlsx, .xls, .csv</strong></li>
                    <li>Row 1 must be headers: <code style="background:var(--surface);padding:1px 5px;border-radius:4px;font-size:.8rem;">SKU, Name, Description, Brand, Unit Price, Cost Price, Category, Unit Type, Tax Rate, Barcode</code></li>
                    <li><strong>SKU</strong> and <strong>Name</strong> columns are required</li>
                    <li>Category should match an existing category name (or leave blank)</li>
                    <li>Maximum file size: <strong>10 MB</strong></li>
                </ul>
            </div>
            <form action="{{ route('product_service.import.products') }}" method="POST" enctype="multipart/form-data" id="importProductsForm">
                @csrf
                <div style="border:2px dashed var(--border);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:border-color .2s;"
                     onclick="document.getElementById('importProductFile').click()"
                     ondragover="event.preventDefault();this.style.borderColor='var(--primary)'"
                     ondragleave="this.style.borderColor='var(--border)'"
                     ondrop="event.preventDefault();this.style.borderColor='var(--border)';document.getElementById('importProductFile').files=event.dataTransfer.files;document.getElementById('importFileName').textContent=event.dataTransfer.files[0]?.name||'No file selected'">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem;color:var(--text-secondary);display:block;margin-bottom:.5rem;"></i>
                    <p style="margin:0 0 .25rem;font-weight:600;color:var(--text-primary);">Click or drag file here</p>
                    <p id="importFileName" style="margin:0;color:var(--text-secondary);font-size:.82rem;">No file selected</p>
                    <input type="file" id="importProductFile" name="file" accept=".xlsx,.xls,.csv" style="display:none"
                           onchange="document.getElementById('importFileName').textContent=this.files[0]?.name||'No file selected'">
                </div>
            </form>
            <x-slot:footer>
                <button type="button" onclick="closeflowexaModal('importProductsModal')"
                        style="padding:.55rem 1.25rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--text-primary);font-weight:600;cursor:pointer;">
                    Cancel
                </button>
                <button type="button" onclick="document.getElementById('importProductsForm').submit()"
                        style="padding:.55rem 1.25rem;border-radius:8px;border:none;background:var(--primary);color:white;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">
                    <i class="fa-solid fa-upload"></i> Import
                </button>
            </x-slot:footer>
        </x-ui.modal>

        {{-- ═══════ EXPORT PRODUCTS MODAL ═══════ --}}
        <x-ui.modal id="exportProductsModal" triggerId="exportProductsModal-trigger-btn" title="Export Products">
            <p style="color:var(--text-secondary);font-size:.9rem;margin:0 0 1.25rem;">Choose a format to download your product data.</p>
            <div style="display:flex;flex-direction:column;gap:.75rem;">
                <a href="{{ route('product_service.export.products', ['format' => 'csv']) }}"
                   style="display:flex;align-items:center;gap:1rem;padding:.85rem 1.25rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:10px;text-decoration:none;transition:border-color .15s;"
                   onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                    <i class="fa-solid fa-file-csv" style="color:#16a34a;font-size:1.25rem;width:1.5rem;text-align:center;"></i>
                    <div>
                        <strong style="display:block;font-size:.9rem;color:var(--headings);">CSV File</strong>
                        <span style="font-size:.78rem;color:var(--text-secondary);">Comma-separated, opens in Excel / Sheets</span>
                    </div>
                </a>
                <a href="{{ route('product_service.export.products', ['format' => 'xlsx']) }}"
                   style="display:flex;align-items:center;gap:1rem;padding:.85rem 1.25rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:10px;text-decoration:none;transition:border-color .15s;"
                   onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                    <i class="fa-solid fa-file-excel" style="color:#16a34a;font-size:1.25rem;width:1.5rem;text-align:center;"></i>
                    <div>
                        <strong style="display:block;font-size:.9rem;color:var(--headings);">Excel (.xlsx)</strong>
                        <span style="font-size:.78rem;color:var(--text-secondary);">Native spreadsheet format</span>
                    </div>
                </a>
                <a href="{{ route('product_service.export.products', ['format' => 'google_sheets']) }}"
                   style="display:flex;align-items:center;gap:1rem;padding:.85rem 1.25rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:10px;text-decoration:none;transition:border-color .15s;"
                   onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                    <i class="fa-brands fa-google-drive" style="color:#4285f4;font-size:1.25rem;width:1.5rem;text-align:center;"></i>
                    <div>
                        <strong style="display:block;font-size:.9rem;color:var(--headings);">Google Sheets</strong>
                        <span style="font-size:.78rem;color:var(--text-secondary);">Export directly to your Google Drive</span>
                    </div>
                </a>
            </div>
            <x-slot:footer>
                <button type="button" onclick="closeflowexaModal('exportProductsModal')"
                        style="padding:.55rem 1.25rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--text-primary);font-weight:600;cursor:pointer;">
                    Close
                </button>
            </x-slot:footer>
        </x-ui.modal>

    </x-ui.grid>
</x-layouts.app>


