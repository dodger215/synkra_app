<x-layouts.app title="Edit Product">
    <x-ui.grid>
        <div class="synkra-dashboard-container" style="padding:2rem;max-width:1100px;margin:0 auto;">
            <div style="margin-bottom:1.5rem;">
                <a href="{{ route('product_service.products.index') }}" style="color:var(--text-secondary);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Inventory
                </a>
                <h1 style="color:var(--headings);margin:0 0 .25rem 0;">Edit Product</h1>
                <p style="color:var(--text-secondary);margin:0;">Update the product details below.</p>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:1.5rem;" />
            @endif
            @if($errors->any())
                <x-ui.alert type="danger" title="Validation Error" :message="$errors->first()" style="margin-bottom:1.5rem;" />
            @endif

            <form id="editForm" action="{{ route('product_service.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">

                    {{-- LEFT COLUMN --}}
                    <div style="display:flex;flex-direction:column;gap:1.5rem;">

                        {{-- Basic Info --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Basic Information</h3>
                            <div style="display:flex;flex-direction:column;gap:1.1rem;">

                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Product Name <span style="color:var(--danger)">*</span></label>
                                    </div>
                                    <x-ui.input label="Enter Product Name" name="name" value="{{ old('name', $product->name) }}" required />
                                </div>

                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Description</label>
                                    </div>
                                    <textarea name="description" rows="3" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;">{{ old('description', $product->description) }}</textarea>
                                </div>

                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                    <div>
                                        <div style="margin-bottom:.4rem;">
                                            <label style="font-size:.85rem;font-weight:600;color:var(--headings);">SKU <span style="color:var(--danger)">*</span></label>
                                        </div>
                                        <x-ui.input label="Enter SKU" name="sku" value="{{ old('sku', $product->sku) }}" required />
                                    </div>
                                    <div>
                                        <div style="margin-bottom:.4rem;">
                                            <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Barcode</label>
                                        </div>
                                        <div style="position:relative;">
                                            <x-ui.input label="Enter Barcode" name="barcode" id="barcodeInput" value="{{ old('barcode', $product->barcode) }}" placeholder="Leave blank to keep existing" />
                                            <div id="barcodeHint" style="margin-top:.3rem;font-size:.78rem;color:var(--text-secondary);display:none;">
                                                <i class="fa-solid fa-circle-info"></i> Current barcode will be kept as-is.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Pricing</h3>
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Selling Price ($)</label>
                                    </div>
                                    <x-ui.input label="Enter Selling Price" name="unit_price" type="number" step="0.01" value="{{ old('unit_price', $product->unit_price) }}" />
                                </div>
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Cost Price ($)</label>
                                    </div>
                                    <x-ui.input label="Enter Cost Price" name="cost_price" type="number" step="0.01" value="{{ old('cost_price', $product->cost_price) }}" />
                                </div>
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Tax Rate (%)</label>
                                    </div>
                                    <x-ui.input label="Enter Tax Rate" name="tax_rate" type="number" step="0.01" value="{{ old('tax_rate', $product->tax_rate) }}" />
                                </div>
                            </div>
                        </div>

                        {{-- Inventory Limits --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Inventory Limits</h3>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Min Stock Level</label>
                                    </div>
                                    <x-ui.input label="Enter Min Stock Level" name="min_stock_level" type="number" value="{{ old('min_stock_level', $product->min_stock_level) }}" />
                                </div>
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Max Stock Level</label>
                                    </div>
                                    <x-ui.input label="Enter Max Stock Level" name="max_stock_level" type="number" value="{{ old('max_stock_level', $product->max_stock_level) }}" />
                                </div>
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Reorder Point</label>
                                    </div>
                                    <x-ui.input label="Enter Reorder Point" name="reorder_point" type="number" value="{{ old('reorder_point', $product->reorder_point) }}" />
                                </div>
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Reorder Quantity</label>
                                    </div>
                                    <x-ui.input label="Enter Reorder Quantity" name="reorder_quantity" type="number" value="{{ old('reorder_quantity', $product->reorder_quantity) }}" />
                                </div>
                            </div>
                        </div>

                        {{-- Dimensions --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Dimensions (Optional)</h3>
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Length (cm)</label>
                                    </div>
                                    <x-ui.input label="Enter Length" name="dimensions[length]" type="number" step="0.01" value="{{ old('dimensions.length', $product->dimensions['length'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Width (cm)</label>
                                    </div>
                                    <x-ui.input label="Enter Width" name="dimensions[width]" type="number" step="0.01" value="{{ old('dimensions.width', $product->dimensions['width'] ?? '') }}" />
                                </div>
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Height (cm)</label>
                                    </div>
                                    <x-ui.input label="Enter Height" name="dimensions[height]" type="number" step="0.01" value="{{ old('dimensions.height', $product->dimensions['height'] ?? '') }}" />
                                </div>
                            </div>
                        </div>

                        {{-- Attributes --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                                <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Product Attributes</h3>
                                <button type="button" onclick="addAttributeField()" style="background:var(--surface-secondary);border:1px solid var(--border);border-radius:6px;padding:.3rem .75rem;font-size:.75rem;color:var(--primary);cursor:pointer;">
                                    <i class="fa-solid fa-plus"></i> Add Attribute
                                </button>
                            </div>
                            <div id="attributesContainer">
                                @php
                                    $attributes = old('attributes', $product->attributes ?? []);
                                @endphp
                                @if(!empty($attributes) && is_array($attributes))
                                    @foreach($attributes as $idx => $attr)
                                        <div class="attribute-row" style="display:flex;gap:.75rem;margin-bottom:.75rem;">
                                            <input type="text" name="attributes[{{ $idx }}][key]" placeholder="Attribute name" value="{{ $attr['key'] ?? '' }}" style="flex:1;padding:.6rem .8rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-size:.85rem;">
                                            <input type="text" name="attributes[{{ $idx }}][value]" placeholder="Value" value="{{ $attr['value'] ?? '' }}" style="flex:1;padding:.6rem .8rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-size:.85rem;">
                                            <button type="button" onclick="this.closest('.attribute-row').remove()" style="background:transparent;border:none;color:var(--danger);cursor:pointer;width:32px;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- Image Upload --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <div style="margin-bottom:1.25rem;">
                                <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Product Images</h3>
                            </div>

                            @if($product->images && count($product->images) > 0)
                                <p style="font-size:.82rem;color:var(--text-secondary);margin:0 0 .75rem;">Existing images — click <i class="fa-solid fa-xmark"></i> to remove on save.</p>
                                <div id="existingImagesGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:.75rem;margin-bottom:1.25rem;">
                                    @foreach($product->images as $i => $img)
                                        <div id="existing-tile-{{ $i }}" style="position:relative;border-radius:8px;overflow:hidden;border:1px solid var(--border);aspect-ratio:1;background:var(--surface-secondary);">
                                            <img src="{{ $product->imageUrl($i) }}" style="width:100%;height:100%;object-fit:cover;">
                                            <button type="button" onclick="markRemove('{{ $img }}', {{ $i }})"
                                                    id="remove-btn-{{ $i }}"
                                                    style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.55);border:none;border-radius:50%;width:22px;height:22px;color:white;cursor:pointer;font-size:.75rem;display:flex;align-items:center;justify-content:center;">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                            @if($i === 0)
                                                <span id="primary-badge-{{ $i }}" style="position:absolute;bottom:4px;left:4px;background:var(--primary);color:white;font-size:.65rem;font-weight:700;padding:2px 6px;border-radius:4px;">PRIMARY</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div id="removeInputs"></div>

                            <div id="dropZone" onclick="document.getElementById('imageInput').click()"
                                style="border:2px dashed var(--border);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;"
                                ondragover="event.preventDefault();this.style.borderColor='var(--primary)';this.style.background='rgba(var(--primary-rgb),.04)'"
                                ondragleave="this.style.borderColor='var(--border)';this.style.background=''"
                                ondrop="handleDrop(event)">
                                <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem;color:var(--text-secondary);margin-bottom:.75rem;display:block;"></i>
                                <p style="margin:0 0 .25rem;color:var(--text-primary);font-weight:600;">Click or drag &amp; drop images here</p>
                                <p style="margin:0;color:var(--text-secondary);font-size:.8rem;">JPEG, PNG, WebP — max 4 MB each</p>
                                <input type="file" id="imageInput" name="new_images[]" multiple accept="image/*" style="display:none;" onchange="previewNewImages(this.files)">
                            </div>

                            <div id="newImagePreviewGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:.75rem;margin-top:1rem;"></div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div style="display:flex;flex-direction:column;gap:1.5rem;">

                        {{-- Organisation --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Organisation</h3>

                            <div style="margin-bottom:1.1rem;">
                                <div style="margin-bottom:.4rem;">
                                    <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Category</label>
                                </div>

                                @if($categories->isEmpty())
                                    <div style="padding:.9rem 1rem;background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.3);border-radius:8px;font-size:.85rem;color:var(--text-secondary);">
                                        <i class="fa-solid fa-circle-exclamation" style="color:#f59e0b;margin-right:.4rem;"></i>
                                        No categories yet.
                                    </div>
                                @else
                                    <select name="category_id" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <div>
                                <div style="margin-bottom:.4rem;">
                                    <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Brand</label>
                                </div>
                                <x-ui.input label="Enter Brand" name="brand" value="{{ old('brand', $product->brand) }}" />
                            </div>
                        </div>

                        {{-- Physical --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1.25rem;font-size:1.05rem;color:var(--headings);">Physical Details</h3>
                            <div style="display:flex;flex-direction:column;gap:1rem;">
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Weight (kg)</label>
                                    </div>
                                    <x-ui.input label="Enter Weight (kg)" name="weight_kg" type="number" step="0.001" value="{{ old('weight_kg', $product->weight_kg) }}" />
                                </div>
                                <div>
                                    <div style="margin-bottom:.4rem;">
                                        <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Unit Type</label>
                                    </div>
                                    <x-ui.input label="Enter Unit Type" name="unit_type" value="{{ old('unit_type', $product->unit_type) }}" placeholder="pcs, kg, box…" />
                                </div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <div style="margin-bottom:1rem;">
                                <h3 style="margin:0;font-size:1.05rem;color:var(--headings);">Status</h3>
                            </div>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <x-ui.switch name="is_active" value="1" :checked="$product->is_active" />
                                <span style="font-size:.9rem;font-weight:500;color:var(--text-primary);">Active Product</span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:1rem;border-top:1px solid var(--border);padding-top:1.5rem;flex-wrap:wrap;">
                    <a href="{{ route('product_service.products.show', $product->id) }}" style="background:transparent;border:1px solid var(--border);color:var(--text-primary);padding:.75rem 1.5rem;border-radius:8px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;">
                        Cancel
                    </a>
                    <button type="submit" style="background:var(--primary);border:none;color:white;padding:.75rem 1.75rem;border-radius:8px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;">
                        <i class="fa-solid fa-floppy-disk"></i> Update Product
                    </button>
                </div>
            </form>
        </div>

        <script>
        // Barcode hint
        const barcodeInput = document.getElementById('barcodeInput');
        const barcodeHint  = document.getElementById('barcodeHint');
        function updateBarcodeHint() {
            barcodeHint.style.display = barcodeInput.value.trim() === '' ? 'block' : 'none';
        }
        barcodeInput.addEventListener('input', updateBarcodeHint);
        updateBarcodeHint();

        // Existing image removal
        const removedPaths = new Set();
        function markRemove(path, index) {
            const container = document.getElementById('removeInputs');
            const tile = document.getElementById('existing-tile-' + index);
            if (removedPaths.has(path)) {
                removedPaths.delete(path);
                tile.style.opacity = '1';
                tile.style.outline = 'none';
                document.getElementById('remove-btn-' + index).style.background = 'rgba(0,0,0,.55)';
                container.querySelector(`input[value="${CSS.escape(path)}"]`)?.remove();
            } else {
                removedPaths.add(path);
                tile.style.opacity = '.35';
                tile.style.outline = '2px solid var(--danger)';
                document.getElementById('remove-btn-' + index).style.background = 'rgba(239,68,68,.8)';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_images[]';
                input.value = path;
                container.appendChild(input);
            }
        }

        // New image upload & preview
        let newFiles = [];
        function previewNewImages(files) {
            Array.from(files).forEach(file => {
                if (newFiles.length >= 8) return;
                newFiles.push(file);
                const reader = new FileReader();
                reader.onload = e => addNewTile(e.target.result, newFiles.length - 1);
                reader.readAsDataURL(file);
            });
            syncNewInput();
        }
        function addNewTile(src, index) {
            const grid = document.getElementById('newImagePreviewGrid');
            const tile = document.createElement('div');
            tile.id = 'new-tile-' + index;
            tile.style.cssText = 'position:relative;border-radius:8px;overflow:hidden;border:1px solid var(--border);aspect-ratio:1;background:var(--surface-secondary);';
            tile.innerHTML = `
                <img src="${src}" style="width:100%;height:100%;object-fit:cover;">
                <button type="button" onclick="removeNewImage(${index})"
                        style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.55);border:none;border-radius:50%;width:22px;height:22px;color:white;cursor:pointer;font-size:.75rem;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <span style="position:absolute;bottom:4px;left:4px;background:var(--primary);color:white;font-size:.65rem;font-weight:700;padding:2px 6px;border-radius:4px;">NEW</span>
            `;
            grid.appendChild(tile);
        }
        function removeNewImage(index) {
            newFiles.splice(index, 1);
            document.getElementById('newImagePreviewGrid').innerHTML = '';
            newFiles.forEach((f, i) => {
                const reader = new FileReader();
                reader.onload = e => addNewTile(e.target.result, i);
                reader.readAsDataURL(f);
            });
            syncNewInput();
        }
        function syncNewInput() {
            const dt = new DataTransfer();
            newFiles.forEach(f => dt.items.add(f));
            document.getElementById('imageInput').files = dt.files;
        }
        function handleDrop(event) {
            event.preventDefault();
            document.getElementById('dropZone').style.borderColor = 'var(--border)';
            document.getElementById('dropZone').style.background = '';
            previewNewImages(event.dataTransfer.files);
        }

        // Attributes
        let attributeIndex = {{ !empty($attributes) && is_array($attributes) ? count($attributes) : 0 }};
        function addAttributeField() {
            const container = document.getElementById('attributesContainer');
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
        </script>
    </x-ui.grid>
</x-layouts.app>
