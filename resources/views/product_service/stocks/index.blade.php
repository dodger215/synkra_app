<x-layouts.app title="Stocks">
    <x-ui.grid>
        <x-slot:head>
            <meta name="description" content="Manage your product inventory.">
        </x-slot:head>

        @php
            $locationTypeOptions = [
                'warehouse' => 'Warehouse',
                'store' => 'Store',
                'retail' => 'Retail',
                'office' => 'Office',
                'other' => 'Other',
            ];

            $importableProducts = $products
                ->filter(fn ($product) => ! $balances->firstWhere('product_id', $product->id))
                ->mapWithKeys(function ($product) {
                    $images = collect($product->images ?? [])
                        ->values()
                        ->map(fn ($_, $index) => $product->imageUrl($index))
                        ->filter()
                        ->values()
                        ->all();

                    return [$product->id => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'barcode' => $product->barcode,
                        'description' => $product->description,
                        'brand' => $product->brand,
                        'category' => $product->category?->name,
                        'unit_price' => $product->unit_price,
                        'cost_price' => $product->cost_price,
                        'unit_type' => $product->unit_type,
                        'weight_kg' => $product->weight_kg,
                        'tax_rate' => $product->tax_rate,
                        'is_active' => (bool) $product->is_active,
                        'images' => $images,
                    ]];
                });
        @endphp

        <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
            @if(session('success'))
                <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
            @endif
            @if(session('error'))
                <x-ui.alert type="danger" title="Error" message="{{ session('error') }}" style="margin-bottom: 2rem;" />
            @endif

            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border); overflow: hidden; padding: 1.5rem;">
                @if($products->isEmpty())
                    <div style="text-align: center; padding: 4rem 2rem;">
                        <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem; color: var(--text-secondary);">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">No Products Found</h3>
                        <p style="color: var(--text-secondary); margin: 0 0 1.5rem 0; font-size: 0.95rem;">You haven't added any products to your inventory yet.</p>
                        <a href="{{ route('product_service.products.create') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none;">Add Your First Product</a>
                    </div>
                @else
                    {{-- Filter Bar --}}
                    @php
                        $locationOptions = [['value' => '', 'label' => 'All Locations']];
                        foreach($locations as $loc) {
                            $locationOptions[] = ['value' => $loc->name, 'label' => $loc->name];
                        }
                    @endphp
                    <x-ui.filter-bar
                        searchPlaceholder="Search by product name, SKU…"
                        :filters="[
                            ['name' => 'location', 'label' => 'Location', 'options' => $locationOptions],
                        ]"
                    >
                        <button type="button" class="flowexa-filter-btn" onclick="openflowexaModal('importStocksFileModal')">
                            <i class="fa-solid fa-file-import"></i> Import
                        </button>
                        <button type="button" class="flowexa-filter-btn" onclick="openflowexaModal('exportStocksModal')">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                    </x-ui.filter-bar>

                    @php
                        $headers = ['Product', 'Quantity', 'Location', 'Stock Balance', 'Stock Adjustment Reason', 'Action'];
                        $rows = [];

                        foreach ($products as $product) {
                            $balance = $balances->firstWhere('product_id', $product->id);

                            $imageUrl = $product->imageUrl();
                            $imageHtml = $imageUrl
                                ? '<img src="' . e($imageUrl) . '" alt="' . e($product->name) . '" style="width:100%;height:100%;object-fit:cover;">'
                                : '<i class="fa-solid fa-image" style="color:var(--text-secondary);font-size:1rem;"></i>';

                            $productCell = new \Illuminate\Support\HtmlString(
                                '<div style="display:flex;align-items:center;gap:1rem;">'
                                . '<div style="width:40px;height:40px;border-radius:8px;background:var(--surface-secondary);display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid var(--border);">' . $imageHtml . '</div>'
                                . '<div><a href="' . e(route('product_service.products.show', $product->id)) . '" style="color:var(--headings);font-weight:600;text-decoration:none;font-size:.95rem;">' . e($product->name) . '</a></div>'
                                . '</div>'
                            );

                            $quantity = $balance ? $balance->quantity_on_hand : 0;
                            $locationName = $balance && $balance->location ? e($balance->location->name) : 'No Location';
                            $binInfo = $balance && $balance->bin ? ' (' . e($balance->bin->name) . ')' : '';
                            $availableStock = $balance ? $balance->quantity_available : 0;

                            $reasonOptions = '';
                            foreach ($reasons as $reason) {
                                $reasonOptions .= '<option value="' . e($reason->id) . '">' . e($reason->reason_name) . ' (' . e($reason->adjustment_type) . ')</option>';
                            }

                            $reasonSelect = new \Illuminate\Support\HtmlString(
                                '<select class="stock-adjustment-reason" data-product-id="' . e($product->id) . '" style="padding:0.5rem;border-radius:6px;border:1px solid var(--border);background:var(--surface);">'
                                . '<option value="">Select Reason</option>'
                                . $reasonOptions
                                . '</select>'
                            );

                            $action = $balance ? new \Illuminate\Support\HtmlString(
                                    '<button type="button" class="flowexa-table-action-btn" title="View Stock Details" onclick="viewStockDetails(' . e(json_encode($product->id)) . ')">View Stock Report</button>'
                                ) : new \Illuminate\Support\HtmlString(
                                    '<button type="button" onclick="importToStock(' . e(json_encode($product->id)) . ')"'
                                    . ' style="padding:.55rem 1rem;background:var(--surface-secondary);border:1px dashed var(--border);border-radius:8px;color:var(--primary);font-size:.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;">'
                                    . '<i class="fa-solid fa-boxes-stacked"></i> Import to Stock'
                                    . '</button>'
                                );

                            $rows[] = [
                                $productCell,
                                $quantity,
                                $locationName . $binInfo,
                                $availableStock,
                                $reasonSelect,
                                $action
                            ];
                        }
                    @endphp

                    <x-ui.table :headers="$headers" :rows="$rows" />
                @endif
            </div>

            <style>
                #importStocksFileModal-trigger-btn,
                #exportStocksModal-trigger-btn { display: none !important; }
            </style>

            {{-- ═══════ STOCK FILE IMPORT MODAL ═══════ --}}
            <x-ui.modal id="importStocksFileModal" triggerId="importStocksFileModal-trigger-btn" title="Import Stock Data">
                <div style="background:var(--surface-secondary);border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:1.25rem;">
                    <h4 style="margin:0 0 .5rem;color:var(--headings);font-size:.95rem;">
                        <i class="fa-solid fa-circle-info" style="color:var(--primary);margin-right:.4rem;"></i> File Format
                    </h4>
                    <ul style="margin:0;padding-left:1.25rem;color:var(--text-secondary);font-size:.85rem;line-height:1.7;">
                        <li>Supported: <strong>.xlsx, .xls, .csv</strong></li>
                        <li>Headers: <code style="background:var(--surface);padding:1px 5px;border-radius:4px;font-size:.8rem;">Product SKU, Location, Quantity, Notes</code></li>
                        <li><strong>Product SKU</strong> must match existing products</li>
                        <li><strong>Location</strong> must match existing stock location names</li>
                        <li>Max size: <strong>10 MB</strong></li>
                    </ul>
                </div>
                <form action="{{ route('product_service.import.stocks') }}" method="POST" enctype="multipart/form-data" id="importStocksFileForm">
                    @csrf
                    <div style="border:2px dashed var(--border);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:border-color .2s;"
                         onclick="document.getElementById('importStocksFile').click()"
                         ondragover="event.preventDefault();this.style.borderColor='var(--primary)'"
                         ondragleave="this.style.borderColor='var(--border)'"
                         ondrop="event.preventDefault();this.style.borderColor='var(--border)';document.getElementById('importStocksFile').files=event.dataTransfer.files;document.getElementById('importStocksFileName').textContent=event.dataTransfer.files[0]?.name||'No file selected'">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem;color:var(--text-secondary);display:block;margin-bottom:.5rem;"></i>
                        <p style="margin:0 0 .25rem;font-weight:600;color:var(--text-primary);">Click or drag file here</p>
                        <p id="importStocksFileName" style="margin:0;color:var(--text-secondary);font-size:.82rem;">No file selected</p>
                        <input type="file" id="importStocksFile" name="file" accept=".xlsx,.xls,.csv" style="display:none"
                               onchange="document.getElementById('importStocksFileName').textContent=this.files[0]?.name||'No file selected'">
                    </div>
                </form>
                <x-slot:footer>
                    <button type="button" onclick="closeflowexaModal('importStocksFileModal')"
                            style="padding:.55rem 1.25rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--text-primary);font-weight:600;cursor:pointer;">
                        Cancel
                    </button>
                    <button type="button" onclick="document.getElementById('importStocksFileForm').submit()"
                            style="padding:.55rem 1.25rem;border-radius:8px;border:none;background:var(--primary);color:white;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">
                        <i class="fa-solid fa-upload"></i> Import
                    </button>
                </x-slot:footer>
            </x-ui.modal>

            {{-- ═══════ STOCK EXPORT MODAL ═══════ --}}
            <x-ui.modal id="exportStocksModal" triggerId="exportStocksModal-trigger-btn" title="Export Stock Data">
                <p style="color:var(--text-secondary);font-size:.9rem;margin:0 0 1.25rem;">Choose format and data set to export.</p>
                <div style="display:flex;flex-direction:column;gap:.75rem;">
                    <a href="{{ route('product_service.export.stock_balances', ['format' => 'csv']) }}"
                       style="display:flex;align-items:center;gap:1rem;padding:.85rem 1.25rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:10px;text-decoration:none;transition:border-color .15s;"
                       onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                        <i class="fa-solid fa-file-csv" style="color:#16a34a;font-size:1.25rem;width:1.5rem;text-align:center;"></i>
                        <div>
                            <strong style="display:block;font-size:.9rem;color:var(--headings);">CSV — Stock Balances</strong>
                            <span style="font-size:.78rem;color:var(--text-secondary);">Current stock levels across all locations</span>
                        </div>
                    </a>
                    <a href="{{ route('product_service.export.stock_balances', ['format' => 'xlsx']) }}"
                       style="display:flex;align-items:center;gap:1rem;padding:.85rem 1.25rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:10px;text-decoration:none;transition:border-color .15s;"
                       onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                        <i class="fa-solid fa-file-excel" style="color:#16a34a;font-size:1.25rem;width:1.5rem;text-align:center;"></i>
                        <div>
                            <strong style="display:block;font-size:.9rem;color:var(--headings);">Excel — Stock Balances</strong>
                            <span style="font-size:.78rem;color:var(--text-secondary);">Native spreadsheet format</span>
                        </div>
                    </a>
                    <a href="{{ route('product_service.export.all_stocks', ['format' => 'xlsx']) }}"
                       style="display:flex;align-items:center;gap:1rem;padding:.85rem 1.25rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:10px;text-decoration:none;transition:border-color .15s;"
                       onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                        <i class="fa-solid fa-file-zipper" style="color:#8b5cf6;font-size:1.25rem;width:1.5rem;text-align:center;"></i>
                        <div>
                            <strong style="display:block;font-size:.9rem;color:var(--headings);">Excel — All Stock Data</strong>
                            <span style="font-size:.78rem;color:var(--text-secondary);">Balances, movements, adjustments, transfers, etc.</span>
                        </div>
                    </a>
                    <a href="{{ route('product_service.export.stock_balances', ['format' => 'google_sheets']) }}"
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
                    <button type="button" onclick="closeflowexaModal('exportStocksModal')"
                            style="padding:.55rem 1.25rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--text-primary);font-weight:600;cursor:pointer;">
                        Close
                    </button>
                </x-slot:footer>
            </x-ui.modal>

        <style>
            #importStocksFileModal-trigger-btn,
            #exportStocksModal-trigger-btn,
            #importStockModal-trigger-btn,
            #createLocationModal-trigger-btn {
                display: none !important;
            }

            #importStockModal .flowexa-modal-container {
                max-width: 820px;
            }

            #importStockModal .flowexa-modal-body {
                max-height: 70vh;
            }
        </style>

        <x-ui.modal title="Import to Stock" id="importStockModal" triggerId="importStockModal-trigger-btn">
            <form id="importStockForm" method="POST" action="">
                @csrf
                <div id="importStockProductDetails"></div>

                <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                        <h4 style="margin:0;font-size:.95rem;color:var(--headings);display:flex;align-items:center;gap:.5rem;">
                            <i class="fa-solid fa-warehouse" style="color:var(--primary);"></i>
                            Stock Location
                        </h4>
                        <a href="{{ route('product_service.stocks.locations.index') }}" style="font-size:.78rem;color:var(--primary);text-decoration:none;">Manage Locations</a>
                    </div>

                    @if($locations->isEmpty())
                        <div id="importLocationEmptyState" style="padding:.9rem 1rem;background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.3);border-radius:8px;font-size:.85rem;color:var(--text-secondary);margin-bottom:.75rem;">
                            <i class="fa-solid fa-circle-exclamation" style="color:#f59e0b;margin-right:.4rem;"></i>
                            No locations yet. Create one below to continue.
                        </div>
                    @endif

                    <select id="importLocationSelect" name="stock_location_id" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;margin-bottom:.75rem;{{ $locations->isEmpty() ? 'display:none;' : '' }}">
                        <option value="">Select location</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ $loc->is_default ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>

                    <button type="button" onclick="openflowexaModal('createLocationModal')"
                            style="width:100%;padding:.55rem 1rem;background:var(--surface-secondary);border:1px dashed var(--border);border-radius:8px;color:var(--primary);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;margin-bottom:1rem;">
                        <i class="fa-solid fa-plus"></i> Create New Location
                    </button>

                    <div>
                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">
                            Opening Quantity <span style="color:var(--danger)">*</span>
                        </label>
                        <input type="number" name="stock_quantity" min="0.01" step="0.01" value="1" required
                               style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;">
                    </div>
                </div>
            </form>
            <x-slot:footer>
                <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('importStockModal')">Cancel</button>
                <button type="button" id="importStockSubmitBtn" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1.25rem;border-radius:8px;font-weight:600;display:inline-flex;align-items:center;gap:.5rem;" onclick="submitImportStock()" {{ $locations->isEmpty() ? 'disabled' : '' }}>
                    <i class="fa-solid fa-boxes-stacked"></i> Import to Stock
                </button>
            </x-slot:footer>
        </x-ui.modal>

        <x-ui.modal id="createLocationModal" triggerId="createLocationModal-trigger-btn" title="Create Stock Location">
            <form id="createLocationForm">
                @include('product_service.stocks.partials.location-form-fields', ['locationTypeOptions' => $locationTypeOptions, 'prefix' => 'import_modal_'])
                <div id="createLocationError" style="display:none;margin-top:1rem;padding:.75rem 1rem;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);border-radius:8px;color:var(--danger);font-size:.85rem;"></div>
            </form>
            <x-slot:footer>
                <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('createLocationModal')">Cancel</button>
                <button type="button" id="createLocationSubmitBtn" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1rem;border-radius:8px;font-weight:600;" onclick="submitLocationModal()">Save Location</button>
            </x-slot:footer>
        </x-ui.modal>

        @include('product_service.stocks.partials.stock-view-modal')

        <script>
            const importableProducts = @json($importableProducts);
            const importStockRouteTemplate = @json(route('product_service.stocks.import', ['productId' => '__PRODUCT_ID__']));

            function formatMoney(value) {
                const amount = Number(value || 0);
                return '$' + amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function renderProductImages(images, name) {
                if (!images || images.length === 0) {
                    return `
                        <div style="width:100%;height:180px;background:var(--surface-secondary);border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--text-secondary);border:1px solid var(--border);">
                            <i class="fa-solid fa-image" style="font-size:2rem;margin-bottom:.5rem;opacity:.4;"></i>
                            <span style="font-size:.85rem;">No images uploaded</span>
                        </div>
                    `;
                }

                const thumbnails = images.length > 1
                    ? `<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(48px,1fr));gap:.5rem;margin-top:.75rem;">
                        ${images.map((src, index) => `
                            <button type="button" onclick="setImportMainImage('${escapeHtml(src)}', this)"
                                style="border-radius:6px;overflow:hidden;border:2px solid ${index === 0 ? 'var(--primary)' : 'var(--border)'};aspect-ratio:1;cursor:pointer;padding:0;background:none;">
                                <img src="${escapeHtml(src)}" alt="${escapeHtml(name)}" style="width:100%;height:100%;object-fit:cover;display:block;">
                            </button>
                        `).join('')}
                    </div>`
                    : '';

                return `
                    <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border);aspect-ratio:1;background:var(--surface-secondary);">
                        <img id="importMainImage" src="${escapeHtml(images[0])}" alt="${escapeHtml(name)}" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    ${thumbnails}
                `;
            }

            function setImportMainImage(src, button) {
                const mainImage = document.getElementById('importMainImage');
                if (mainImage) {
                    mainImage.src = src;
                }

                document.querySelectorAll('#importStockProductDetails button[type="button"]').forEach((el) => {
                    el.style.borderColor = 'var(--border)';
                });

                if (button) {
                    button.style.borderColor = 'var(--primary)';
                }
            }

            function renderProductDetails(product) {
                const margin = Number(product.unit_price) > 0
                    ? ((Number(product.unit_price) - Number(product.cost_price)) / Number(product.unit_price)) * 100
                    : 0;

                return `
                    <div style="display:grid;grid-template-columns:220px 1fr;gap:1.25rem;align-items:start;">
                        <div>${renderProductImages(product.images, product.name)}</div>
                        <div>
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:.75rem;">
                                <div>
                                    <h3 style="margin:0 0 .35rem 0;font-size:1.1rem;color:var(--headings);">${escapeHtml(product.name)}</h3>
                                    <p style="margin:0;color:var(--text-secondary);font-size:.85rem;display:flex;flex-wrap:wrap;gap:.75rem;">
                                        <span><i class="fa-solid fa-barcode"></i> ${escapeHtml(product.sku)}</span>
                                        ${product.barcode ? `<span style="font-family:monospace;">${escapeHtml(product.barcode)}</span>` : ''}
                                        ${product.category ? `<span><i class="fa-solid fa-tags"></i> ${escapeHtml(product.category)}</span>` : ''}
                                    </p>
                                </div>
                                <span style="font-size:.75rem;font-weight:700;padding:.25rem .65rem;border-radius:999px;${product.is_active ? 'background:rgba(16,185,129,.12);color:#059669;' : 'background:rgba(239,68,68,.12);color:var(--danger);'}">
                                    ${product.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </div>

                            <p style="margin:0 0 1rem 0;color:var(--text-primary);font-size:.9rem;line-height:1.55;">
                                ${escapeHtml(product.description || 'No description provided.')}
                            </p>

                            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;">
                                <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.4px;color:var(--text-secondary);margin-bottom:.2rem;">Brand</div>
                                    <div style="font-size:.9rem;font-weight:600;color:var(--text-primary);">${escapeHtml(product.brand || 'Not specified')}</div>
                                </div>
                                <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.4px;color:var(--text-secondary);margin-bottom:.2rem;">Unit Type</div>
                                    <div style="font-size:.9rem;font-weight:600;color:var(--text-primary);text-transform:capitalize;">${escapeHtml(product.unit_type || '—')}</div>
                                </div>
                                <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.4px;color:var(--text-secondary);margin-bottom:.2rem;">Selling Price</div>
                                    <div style="font-size:.9rem;font-weight:600;color:var(--text-primary);">${formatMoney(product.unit_price)}</div>
                                </div>
                                <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.4px;color:var(--text-secondary);margin-bottom:.2rem;">Cost Price</div>
                                    <div style="font-size:.9rem;font-weight:600;color:var(--text-primary);">${formatMoney(product.cost_price)}</div>
                                </div>
                                <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.4px;color:var(--text-secondary);margin-bottom:.2rem;">Profit Margin</div>
                                    <div style="font-size:.9rem;font-weight:600;color:${margin >= 0 ? '#059669' : 'var(--danger)'};">${margin.toFixed(1)}%</div>
                                </div>
                                <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.4px;color:var(--text-secondary);margin-bottom:.2rem;">Weight</div>
                                    <div style="font-size:.9rem;font-weight:600;color:var(--text-primary);">${product.weight_kg ? escapeHtml(product.weight_kg) + ' kg' : '—'}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            function importToStock(productId) {
                const product = importableProducts[productId];
                if (!product) {
                    return;
                }

                document.getElementById('importStockForm').action = importStockRouteTemplate.replace('__PRODUCT_ID__', productId);
                document.getElementById('importStockProductDetails').innerHTML = renderProductDetails(product);
                openflowexaModal('importStockModal');
            }

            function submitImportStock() {
                document.getElementById('importStockForm').submit();
            }

            async function submitLocationModal() {
                const form = document.getElementById('createLocationForm');
                const errorEl = document.getElementById('createLocationError');
                const submitBtn = document.getElementById('createLocationSubmitBtn');
                const importSubmitBtn = document.getElementById('importStockSubmitBtn');
                if (!form) {
                    return;
                }

                const formData = new FormData(form);
                formData.append('_token', '{{ csrf_token() }}');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Saving…';
                }
                if (errorEl) {
                    errorEl.style.display = 'none';
                }

                try {
                    const response = await fetch('{{ route('product_service.stocks.locations.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        const message = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to create location.');
                        if (errorEl) {
                            errorEl.textContent = message;
                            errorEl.style.display = 'block';
                        }
                        return;
                    }

                    const select = document.getElementById('importLocationSelect');
                    const emptyState = document.getElementById('importLocationEmptyState');

                    if (select) {
                        const option = document.createElement('option');
                        option.value = data.location.id;
                        option.textContent = data.location.name;
                        option.selected = true;
                        select.appendChild(option);
                        select.style.display = 'block';
                        select.required = true;
                    }

                    if (emptyState) {
                        emptyState.style.display = 'none';
                    }

                    if (importSubmitBtn) {
                        importSubmitBtn.disabled = false;
                    }

                    form.reset();
                    const activeCheckbox = form.querySelector('[name="is_active"]');
                    if (activeCheckbox) {
                        activeCheckbox.checked = true;
                    }

                    closeflowexaModal('createLocationModal');
                } catch (error) {
                    if (errorEl) {
                        errorEl.textContent = 'Network error. Please try again.';
                        errorEl.style.display = 'block';
                    }
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Location';
                    }
                }
            }
        </script>
    </x-ui.grid>
</x-layouts.app>
