<x-layouts.app title="Create Product">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:1100px;margin:0 auto;">
            <div style="margin-bottom:1.5rem;">
                <a href="{{ route('product_service.products.index') }}" style="color:var(--text-secondary);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Inventory
                </a>
                <h1 style="color:var(--headings);margin:0 0 .25rem 0;">Add New Product</h1>
                <p style="color:var(--text-secondary);margin:0;">Fill out the form below to register a new product in your catalog.</p>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:1.5rem;" />
            @endif
            @if($errors->any())
                <x-ui.alert type="danger" title="Validation Error" :message="$errors->first()" style="margin-bottom:1.5rem;" />
            @endif

            <form id="productForm" action="{{ route('product_service.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="import_to_stock" id="import_to_stock" value="0">

                <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">

                    {{-- LEFT COLUMN --}}
                    <div style="display:flex;flex-direction:column;gap:1.5rem;">

                        {{-- Basic Info --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Basic Information</h3>
                            <div style="display:flex;flex-direction:column;gap:1.1rem;">
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Product Name <span style="color:var(--danger)">*</span></label>
                                        <x-ui.tooltip text="?" tooltip="The full commercial name of the product as it will appear on invoices and the POS." />
                                    </div>
                                    <x-ui.input label="Enter Product Name" name="name" value="{{ old('name', $restored['name'] ?? '') }}" required />
                                </div>

                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Description</label>
                                        <x-ui.tooltip text="?" tooltip="Optional. A short summary of the product shown to staff on the product detail page." />
                                    </div>
                                    <textarea name="description" rows="3" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;">{{ old('description', $restored['description'] ?? '') }}</textarea>
                                </div>

                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                    <div>
                                        <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                            <label style="font-size:.85rem;font-weight:600;color:var(--headings);">SKU <span style="color:var(--danger)">*</span></label>
                                            <x-ui.tooltip text="?" tooltip="Stock Keeping Unit — a unique internal code you assign to each product." />
                                        </div>
                                        <x-ui.input label="Enter SKU" name="sku" value="{{ old('sku', $restored['sku'] ?? '') }}" required />
                                    </div>
                                    <div>
                                        <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                            <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Barcode</label>
                                            <x-ui.tooltip text="?" tooltip="UPC / EAN / GTIN barcode. Leave blank and the system will auto-generate one for you." />
                                        </div>
                                        <div style="position:relative;">
                                            <x-ui.input label="Enter Barcode" name="barcode" id="barcodeInput" value="{{ old('barcode', $restored['barcode'] ?? '') }}" placeholder="Leave blank to auto-generate" />
                                            <div id="barcodeHint" style="margin-top:.3rem;font-size:.78rem;color:var(--text-secondary);display:none;">
                                                <i class="fa-solid fa-circle-info"></i> A barcode will be auto-generated on save.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Pricing</h3>
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Selling Price ($)</label>
                                        <x-ui.tooltip text="?" tooltip="The price charged to customers." />
                                    </div>
                                    <x-ui.input label="Enter Selling Price" name="unit_price" type="number" step="0.01" value="{{ old('unit_price', $restored['unit_price'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Cost Price ($)</label>
                                        <x-ui.tooltip text="?" tooltip="What you paid for the product." />
                                    </div>
                                    <x-ui.input label="Enter Cost Price" name="cost_price" type="number" step="0.01" value="{{ old('cost_price', $restored['cost_price'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Tax Rate (%)</label>
                                        <x-ui.tooltip text="?" tooltip="The VAT / sales tax percentage applied at checkout." />
                                    </div>
                                    <x-ui.input label="Enter Tax Rate" name="tax_rate" type="number" step="0.01" value="{{ old('tax_rate', $restored['tax_rate'] ?? '') }}" />
                                </div>
                            </div>
                        </div>

                        {{-- Inventory Limits --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Inventory Limits</h3>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Min Stock Level</label>
                                        <x-ui.tooltip text="?" tooltip="Alert when stock falls below this number." />
                                    </div>
                                    <x-ui.input label="Enter Min Stock Level" name="min_stock_level" type="number" value="{{ old('min_stock_level', $restored['min_stock_level'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Max Stock Level</label>
                                        <x-ui.tooltip text="?" tooltip="Maximum quantity you want to hold." />
                                    </div>
                                    <x-ui.input label="Enter Max Stock Level" name="max_stock_level" type="number" value="{{ old('max_stock_level', $restored['max_stock_level'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Reorder Point</label>
                                        <x-ui.tooltip text="?" tooltip="When stock drops to this level, a purchase order suggestion is triggered." />
                                    </div>
                                    <x-ui.input label="Enter Reorder Point" name="reorder_point" type="number" value="{{ old('reorder_point', $restored['reorder_point'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Reorder Quantity</label>
                                        <x-ui.tooltip text="?" tooltip="How many units to order each time stock hits the reorder point." />
                                    </div>
                                    <x-ui.input label="Enter Reorder Quantity" name="reorder_quantity" type="number" value="{{ old('reorder_quantity', $restored['reorder_quantity'] ?? '') }}" />
                                </div>
                            </div>
                        </div>

                        {{-- Dimensions --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Dimensions (Optional)</h3>
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Length (cm)</label>
                                        <x-ui.tooltip text="?" tooltip="Product length in centimeters." />
                                    </div>
                                    <x-ui.input label="Enter Length" name="dimensions[length]" type="number" step="0.01" value="{{ old('dimensions.length', $restored['dimensions']['length'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Width (cm)</label>
                                        <x-ui.tooltip text="?" tooltip="Product width in centimeters." />
                                    </div>
                                    <x-ui.input label="Enter Width" name="dimensions[width]" type="number" step="0.01" value="{{ old('dimensions.width', $restored['dimensions']['width'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Height (cm)</label>
                                        <x-ui.tooltip text="?" tooltip="Product height in centimeters." />
                                    </div>
                                    <x-ui.input label="Enter Height" name="dimensions[height]" type="number" step="0.01" value="{{ old('dimensions.height', $restored['dimensions']['height'] ?? '') }}" />
                                </div>
                            </div>
                        </div>

                        {{-- Attributes --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                                <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Product Attributes</h3>
                                <button type="button" onclick="addAttributeField()" style="background:var(--surface-secondary);border:1px solid var(--border);border-radius:6px;padding:.3rem .75rem;font-size:.75rem;color:var(--primary);cursor:pointer;">
                                    <i class="fa-solid fa-plus"></i> Add Attribute
                                </button>
                            </div>
                            <div id="attributesContainer">
                                @php
                                    $oldAttributes = old('attributes', $restored['attributes'] ?? []);
                                @endphp
                                @if(!empty($oldAttributes))
                                    @foreach($oldAttributes as $key => $value)
                                        <div class="attribute-row" style="display:flex;gap:.75rem;margin-bottom:.75rem;">
                                            <input type="text" name="attributes[{{ $loop->index }}][key]" placeholder="Attribute name (e.g., Color)" value="{{ $key }}" style="flex:1;padding:.6rem .8rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-size:.85rem;">
                                            <input type="text" name="attributes[{{ $loop->index }}][value]" placeholder="Value (e.g., Red)" value="{{ $value }}" style="flex:1;padding:.6rem .8rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-size:.85rem;">
                                            <button type="button" onclick="this.closest('.attribute-row').remove()" style="background:transparent;border:none;color:var(--danger);cursor:pointer;width:32px;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- Image Upload --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
                                <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Product Images</h3>
                                <x-ui.tooltip text="?" tooltip="Upload up to 8 images (JPEG, PNG, WebP). The first image is used as the primary thumbnail." />
                            </div>

                            <div id="dropZone" onclick="document.getElementById('imageInput').click()"
                                style="border:2px dashed var(--border);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;"
                                ondragover="event.preventDefault();this.style.borderColor='var(--primary)';this.style.background='rgba(var(--primary-rgb),.04)'"
                                ondragleave="this.style.borderColor='var(--border)';this.style.background=''"
                                ondrop="handleDrop(event)">
                                <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem;color:var(--text-secondary);margin-bottom:.75rem;display:block;"></i>
                                <p style="margin:0 0 .25rem;color:var(--text-primary);font-weight:600;">Click or drag &amp; drop images here</p>
                                <p style="margin:0;color:var(--text-secondary);font-size:.8rem;">JPEG, PNG, WebP — max 4 MB each</p>
                                <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="display:none;" onchange="previewImages(this.files)">
                            </div>

                            <div id="imagePreviewGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:.75rem;margin-top:1rem;"></div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div style="display:flex;flex-direction:column;gap:1.5rem;">

                        {{-- Organisation --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Organisation</h3>

                            <div style="margin-bottom:1.1rem;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.4rem;">
                                    <div style="display:flex;align-items:center;gap:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Category</label>
                                        <x-ui.tooltip text="?" tooltip="Group products by category for filtering and reporting." />
                                    </div>
                                    <a href="{{ route('product_service.categories.index') }}" style="font-size:.78rem;color:var(--primary);text-decoration:none;">Manage</a>
                                </div>

                                @if($categories->isEmpty())
                                    <div id="categoryEmptyState" style="padding:.9rem 1rem;background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.3);border-radius:8px;font-size:.85rem;color:var(--text-secondary);margin-bottom:.75rem;">
                                        <i class="fa-solid fa-circle-exclamation" style="color:#f59e0b;margin-right:.4rem;"></i>
                                        No categories yet.
                                    </div>
                                @endif

                                <select id="categorySelect" name="category_id" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;margin-bottom:.6rem;{{ $categories->isEmpty() ? 'display:none;' : '' }}">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (old('category_id', $restored['category_id'] ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>

                                <button type="button" onclick="openflowexaModal('createCategoryModal')"
                                        style="width:100%;padding:.55rem 1rem;background:var(--surface-secondary);border:1px dashed var(--border);border-radius:8px;color:var(--primary);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;">
                                    <i class="fa-solid fa-plus"></i> Create New Category
                                </button>
                            </div>

                            <div>
                                <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                    <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Brand</label>
                                    <x-ui.tooltip text="?" tooltip="The manufacturer or brand name." />
                                </div>
                                <x-ui.input label="Enter Brand" name="brand" value="{{ old('brand', $restored['brand'] ?? '') }}" />
                            </div>
                        </div>

                        {{-- Physical --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Physical Details</h3>
                            <div style="display:flex;flex-direction:column;gap:1rem;">
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Weight (kg)</label>
                                        <x-ui.tooltip text="?" tooltip="Used for shipping cost calculations." />
                                    </div>
                                    <x-ui.input label="Enter Weight (kg)" name="weight_kg" type="number" step="0.001" value="{{ old('weight_kg', $restored['weight_kg'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Unit Type</label>
                                        <x-ui.tooltip text="?" tooltip="How the product is sold — e.g. pcs, kg, box, litre, pair." />
                                    </div>
                                    <x-ui.input label="Enter Unit Type" name="unit_type" value="{{ old('unit_type', $restored['unit_type'] ?? '') }}" placeholder="pcs, kg, box…" />
                                </div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:1rem;">
                                <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Status</h3>
                                <x-ui.tooltip text="?" tooltip="Inactive products are hidden from POS and ordering flows." />
                            </div>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <x-ui.switch name="is_active" value="1" :checked="true" />
                                <span style="font-size:.9rem;font-weight:500;color:var(--text-primary);">Active Product</span>
                            </div>
                        </div>

                        {{-- Initial Stock Import toggle --}}
                        <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                                <div>
                                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem;">
                                        <i class="fa-solid fa-warehouse" style="color:var(--primary);"></i>
                                        <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Initial Stock Import</h3>
                                    </div>
                                    <p style="margin:0;font-size:.82rem;color:var(--text-secondary);">Receive opening stock when creating this product.</p>
                                </div>
                                <div style="display:flex;align-items:center;gap:.75rem;flex-shrink:0;">
                                    <span id="stockToggleLabel" style="font-size:.85rem;font-weight:500;color:var(--text-secondary);">Off</span>
                                    <x-ui.switch name="enable_stock_import" value="1" :checked="!empty($restored['stock_location_id'])" id="stockImportToggle" onChange="toggleStockImportPanel()" />
                                </div>
                            </div>
                        </div>

                        {{-- Import to Stock panel --}}
                        <div id="stockImportPanel" class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--primary);display:none;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                <div style="display:flex;align-items:center;gap:.5rem;">
                                    <i class="fa-solid fa-boxes-stacked" style="color:var(--primary);"></i>
                                    <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Stock Details</h3>
                                </div>
                                <a href="{{ route('product_service.stocks.locations.index') }}" style="font-size:.78rem;color:var(--primary);text-decoration:none;">Manage Locations</a>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:1rem;">
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Location <span style="color:var(--danger)">*</span></label>
                                        <x-ui.tooltip text="?" tooltip="The warehouse or storage location where this stock will be received." />
                                    </div>

                                    @if($locations->isEmpty())
                                        <div id="locationEmptyState" style="padding:.9rem 1rem;background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.3);border-radius:8px;font-size:.85rem;color:var(--text-secondary);margin-bottom:.75rem;">
                                            <i class="fa-solid fa-circle-exclamation" style="color:#f59e0b;margin-right:.4rem;"></i>
                                            No locations yet.
                                        </div>
                                    @endif

                                    <select id="locationSelect" name="stock_location_id" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;margin-bottom:.6rem;{{ $locations->isEmpty() ? 'display:none;' : '' }}">
                                        <option value="">Select location</option>
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}" {{ (old('stock_location_id', $restored['stock_location_id'] ?? '') == $loc->id) ? 'selected' : '' }}>{{ $loc->name }}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" onclick="openflowexaModal('createLocationModal')"
                                            style="width:100%;padding:.55rem 1rem;background:var(--surface-secondary);border:1px dashed var(--border);border-radius:8px;color:var(--primary);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;">
                                        <i class="fa-solid fa-plus"></i> Create New Location
                                    </button>
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Opening Quantity <span style="color:var(--danger)">*</span></label>
                                        <x-ui.tooltip text="?" tooltip="The quantity being received into stock right now." />
                                    </div>
                                    <x-ui.input label="Enter Opening Quantity" name="stock_quantity" type="number" step="1" value="{{ old('stock_quantity', $restored['stock_quantity'] ?? '0') }}" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:1rem;border-top:1px solid var(--border);padding-top:1.5rem;flex-wrap:wrap;">
                    <a href="{{ route('product_service.products.index') }}" style="background:transparent;border:1px solid var(--border);color:var(--text-primary);padding:.75rem 1.5rem;border-radius:8px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;">
                        Cancel
                    </a>
                    <button type="submit" style="background:var(--surface-secondary);border:1px solid var(--border);color:var(--text-primary);padding:.75rem 1.5rem;border-radius:8px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Product
                    </button>
                    <button type="button" onclick="submitWithStock()"
                            style="background:var(--primary);border:none;color:white;padding:.75rem 1.75rem;border-radius:8px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;">
                        <i class="fa-solid fa-warehouse"></i> Save &amp; Import to Stock
                    </button>
                </div>
            </form>
        </div>

        @php
            $locationTypeOptions = [
                'warehouse' => 'Warehouse',
                'store' => 'Store',
                'retail' => 'Retail',
                'office' => 'Office',
                'other' => 'Other',
            ];
        @endphp

        <style>#createCategoryModal-trigger-btn, #createLocationModal-trigger-btn { display:none !important; }</style>

        <x-ui.modal id="createCategoryModal" triggerId="createCategoryModal-trigger-btn" title="Create Category">
            <form id="createCategoryForm">
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <x-ui.input name="name" label="Category Name" placeholder="e.g. Electronics" required />
                    <div>
                        <label style="font-size:.85rem;font-weight:500;color:var(--text-primary);display:block;margin-bottom:.4rem;">Description</label>
                        <textarea name="description" rows="3" style="width:100%;padding:.75rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;" placeholder="Optional description"></textarea>
                    </div>
                    <div id="createCategoryError" style="display:none;padding:.75rem 1rem;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);border-radius:8px;color:var(--danger);font-size:.85rem;"></div>
                </div>
            </form>
            <x-slot:footer>
                <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('createCategoryModal')">Cancel</button>
                <button type="button" id="createCategorySubmitBtn" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1rem;border-radius:8px;font-weight:600;" onclick="submitCategoryModal()">Save Category</button>
            </x-slot:footer>
        </x-ui.modal>

        <x-ui.modal id="createLocationModal" triggerId="createLocationModal-trigger-btn" title="Create Stock Location">
            <form id="createLocationForm">
                @include('product_service.stocks.partials.location-form-fields', ['locationTypeOptions' => $locationTypeOptions, 'prefix' => 'modal_'])
                <div id="createLocationError" style="display:none;margin-top:1rem;padding:.75rem 1rem;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);border-radius:8px;color:var(--danger);font-size:.85rem;"></div>
            </form>
            <x-slot:footer>
                <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('createLocationModal')">Cancel</button>
                <button type="button" id="createLocationSubmitBtn" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1rem;border-radius:8px;font-weight:600;" onclick="submitLocationModal()">Save Location</button>
            </x-slot:footer>
        </x-ui.modal>

        <script>
        // Barcode hint
        const barcodeInput = document.getElementById('barcodeInput');
        const barcodeHint  = document.getElementById('barcodeHint');
        function updateBarcodeHint() {
            if (barcodeHint) barcodeHint.style.display = barcodeInput.value.trim() === '' ? 'block' : 'none';
        }
        if (barcodeInput) {
            barcodeInput.addEventListener('input', updateBarcodeHint);
            updateBarcodeHint();
        }

        // Image upload & preview
        let uploadedFiles = [];
        function previewImages(files) {
            Array.from(files).forEach(file => {
                if (uploadedFiles.length >= 8) return;
                uploadedFiles.push(file);
                const reader = new FileReader();
                reader.onload = e => addImageTile(e.target.result, uploadedFiles.length - 1);
                reader.readAsDataURL(file);
            });
            syncFileInput();
        }
        function addImageTile(src, index) {
            const grid = document.getElementById('imagePreviewGrid');
            if (!grid) return;
            const tile = document.createElement('div');
            tile.id = 'tile-' + index;
            tile.style.cssText = 'position:relative;border-radius:8px;overflow:hidden;border:1px solid var(--border);aspect-ratio:1;background:var(--surface-secondary);';
            tile.innerHTML = `
                <img src="${src}" style="width:100%;height:100%;object-fit:cover;">
                <button type="button" onclick="removeImage(${index})"
                        style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.55);border:none;border-radius:50%;width:22px;height:22px;color:white;cursor:pointer;font-size:.75rem;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                ${index === 0 ? '<span style="position:absolute;bottom:4px;left:4px;background:var(--primary);color:white;font-size:.65rem;font-weight:700;padding:2px 6px;border-radius:4px;">PRIMARY</span>' : ''}
            `;
            grid.appendChild(tile);
        }
        function removeImage(index) {
            uploadedFiles.splice(index, 1);
            const grid = document.getElementById('imagePreviewGrid');
            if (grid) grid.innerHTML = '';
            uploadedFiles.forEach((f, i) => {
                const reader = new FileReader();
                reader.onload = e => addImageTile(e.target.result, i);
                reader.readAsDataURL(f);
            });
            syncFileInput();
        }
        function syncFileInput() {
            const dt = new DataTransfer();
            uploadedFiles.forEach(f => dt.items.add(f));
            const imageInput = document.getElementById('imageInput');
            if (imageInput) imageInput.files = dt.files;
        }
        function handleDrop(event) {
            event.preventDefault();
            const dropZone = document.getElementById('dropZone');
            if (dropZone) {
                dropZone.style.borderColor = 'var(--border)';
                dropZone.style.background = '';
            }
            previewImages(event.dataTransfer.files);
        }

        // Attributes
        let attributeIndex = {{ count($oldAttributes ?? []) }};
        function addAttributeField() {
            const container = document.getElementById('attributesContainer');
            if (!container) return;
            const row = document.createElement('div');
            row.className = 'attribute-row';
            row.style.cssText = 'display:flex;gap:.75rem;margin-bottom:.75rem;';
            row.innerHTML = `
                <input type="text" name="attributes[${attributeIndex}][key]" placeholder="Attribute name (e.g., Color)" style="flex:1;padding:.6rem .8rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-size:.85rem;">
                <input type="text" name="attributes[${attributeIndex}][value]" placeholder="Value (e.g., Red)" style="flex:1;padding:.6rem .8rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-size:.85rem;">
                <button type="button" onclick="this.closest('.attribute-row').remove()" style="background:transparent;border:none;color:var(--danger);cursor:pointer;width:32px;">
                    <i class="fa-solid fa-trash"></i>
                </button>
            `;
            container.appendChild(row);
            attributeIndex++;
        }

        // Initial Stock Import toggle — show/hide panel only
        function toggleStockImportPanel() {
            const toggle = document.getElementById('stockImportToggle');
            const panel = document.getElementById('stockImportPanel');
            const label = document.getElementById('stockToggleLabel');
            if (!toggle || !panel) return;

            const isOn = toggle.checked;
            panel.style.display = isOn ? 'block' : 'none';
            if (label) {
                label.textContent = isOn ? 'On' : 'Off';
                label.style.color = isOn ? 'var(--primary)' : 'var(--text-secondary)';
            }
        }

        // Init stock panel visibility from toggle state
        document.addEventListener('DOMContentLoaded', function() {
            toggleStockImportPanel();
        });

        // Save & Import to Stock
        function submitWithStock() {
            const toggle = document.getElementById('stockImportToggle');
            const panel = document.getElementById('stockImportPanel');
            const importField = document.getElementById('import_to_stock');

            if (toggle && !toggle.checked) {
                toggle.checked = true;
                toggleStockImportPanel();
                if (panel) panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            if (importField) importField.value = '1';
            const form = document.getElementById('productForm');
            if (form) form.submit();
        }

        // Category modal — AJAX create
        async function submitCategoryModal() {
            const form = document.getElementById('createCategoryForm');
            const errorEl = document.getElementById('createCategoryError');
            const btn = document.getElementById('createCategorySubmitBtn');
            if (!form) return;

            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');

            if (btn) { btn.disabled = true; btn.textContent = 'Saving…'; }
            if (errorEl) errorEl.style.display = 'none';

            try {
                const response = await fetch('{{ route("product_service.categories.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (!response.ok) {
                    const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to create category.');
                    if (errorEl) { errorEl.textContent = msg; errorEl.style.display = 'block'; }
                    return;
                }

                const select = document.getElementById('categorySelect');
                const emptyState = document.getElementById('categoryEmptyState');
                if (select) {
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.textContent = data.category.name;
                    option.selected = true;
                    select.appendChild(option);
                    select.style.display = 'block';
                }
                if (emptyState) emptyState.style.display = 'none';

                form.reset();
                closeflowexaModal('createCategoryModal');
            } catch (e) {
                if (errorEl) { errorEl.textContent = 'Network error. Please try again.'; errorEl.style.display = 'block'; }
            } finally {
                if (btn) { btn.disabled = false; btn.textContent = 'Save Category'; }
            }
        }

        // Location modal — AJAX create
        async function submitLocationModal() {
            const form = document.getElementById('createLocationForm');
            const errorEl = document.getElementById('createLocationError');
            const btn = document.getElementById('createLocationSubmitBtn');
            if (!form) return;

            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');

            if (btn) { btn.disabled = true; btn.textContent = 'Saving…'; }
            if (errorEl) errorEl.style.display = 'none';

            try {
                const response = await fetch('{{ route("product_service.stocks.locations.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (!response.ok) {
                    const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to create location.');
                    if (errorEl) { errorEl.textContent = msg; errorEl.style.display = 'block'; }
                    return;
                }

                const select = document.getElementById('locationSelect');
                const emptyState = document.getElementById('locationEmptyState');
                if (select) {
                    const option = document.createElement('option');
                    option.value = data.location.id;
                    option.textContent = data.location.name;
                    option.selected = true;
                    select.appendChild(option);
                    select.style.display = 'block';
                }
                if (emptyState) emptyState.style.display = 'none';

                form.reset();
                const activeCheckbox = form.querySelector('[name="is_active"]');
                if (activeCheckbox) activeCheckbox.checked = true;
                closeflowexaModal('createLocationModal');
            } catch (e) {
                if (errorEl) { errorEl.textContent = 'Network error. Please try again.'; errorEl.style.display = 'block'; }
            } finally {
                if (btn) { btn.disabled = false; btn.textContent = 'Save Location'; }
            }
        }

        </script>
    </x-ui.grid>
</x-layouts.app>
