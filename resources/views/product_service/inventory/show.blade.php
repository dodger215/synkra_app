<x-layouts.app title="{{ $product->name }}">
    <x-ui.grid>
        <div class="synkra-dashboard-container" style="padding:2rem;max-width:1200px;margin:0 auto;">
            <div style="margin-bottom:1.5rem;">
                <a href="{{ route('product_service.products.index') }}" style="color:var(--text-secondary);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Inventory
                </a>

                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;">
                    <div>
                        <h1 style="color:var(--headings);margin:0 0 .4rem;display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                            {{ $product->name }}
                            @if($product->is_active)
                                <x-ui.badge variant="success" pill="true">Active</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger" pill="true">Inactive</x-ui.badge>
                            @endif
                        </h1>
                        <p style="color:var(--text-secondary);margin:0;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;font-size:.9rem;">
                            <span><i class="fa-solid fa-barcode" style="margin-right:.3rem;"></i> {{ $product->sku }}</span>
                            @if($product->barcode)
                                <span style="font-family:monospace;">{{ $product->barcode }}</span>
                            @endif
                            @if($product->category)
                                <span><i class="fa-solid fa-tags" style="margin-right:.3rem;"></i> {{ $product->category->name }}</span>
                            @endif
                        </p>
                    </div>
                    <div style="display:flex;gap:.75rem;">
                        <a href="{{ route('product_service.products.edit', $product->id) }}" style="background:var(--primary);border:none;color:white;padding:.6rem 1.25rem;border-radius:8px;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:.5rem;font-size:.9rem;">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <form action="{{ route('product_service.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:transparent;border:1px solid var(--danger);color:var(--danger);padding:.6rem 1.25rem;border-radius:8px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.5rem;font-size:.9rem;">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:1.5rem;" />
            @endif

            <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">

                {{-- ── LEFT COLUMN ──────────────────────────────── --}}
                <div style="display:flex;flex-direction:column;gap:1.5rem;">

                    {{-- Description --}}
                    <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                        <h3 style="margin:0 0 1rem;font-size:1.05rem;color:var(--headings);border-bottom:1px solid var(--border);padding-bottom:.6rem;">Product Overview</h3>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                            <div>
                                <div style="color:var(--text-secondary);font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600;margin-bottom:.25rem;">Description</div>
                                <div style="color:var(--text-primary);font-size:.95rem;line-height:1.6;">{{ $product->description ?: 'No description provided.' }}</div>
                            </div>
                            <div>
                                <div style="color:var(--text-secondary);font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600;margin-bottom:.25rem;">Brand</div>
                                <div style="color:var(--text-primary);font-size:.95rem;">{{ $product->brand ?: 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing + Inventory side by side --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1rem;font-size:1.05rem;color:var(--headings);border-bottom:1px solid var(--border);padding-bottom:.6rem;">Pricing</h3>
                            <div style="display:flex;flex-direction:column;gap:.9rem;">
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="color:var(--text-secondary);font-size:.9rem;">Selling Price</span>
                                    <span style="color:var(--text-primary);font-weight:600;">${{ number_format($product->unit_price, 2) }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="color:var(--text-secondary);font-size:.9rem;">Cost Price</span>
                                    <span style="color:var(--text-primary);font-weight:600;">${{ number_format($product->cost_price, 2) }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="color:var(--text-secondary);font-size:.9rem;">Profit Margin</span>
                                    @php
                                        $margin = $product->unit_price > 0
                                            ? (($product->unit_price - $product->cost_price) / $product->unit_price) * 100
                                            : 0;
                                    @endphp
                                    <span style="color:{{ $margin >= 0 ? 'var(--success, #059669)' : 'var(--danger)' }};font-weight:600;">{{ number_format($margin, 1) }}%</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;border-top:1px dashed var(--border);padding-top:.75rem;">
                                    <span style="color:var(--text-secondary);font-size:.9rem;">Tax Rate</span>
                                    <span style="color:var(--text-primary);font-weight:600;">{{ number_format($product->tax_rate, 2) }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                            <h3 style="margin:0 0 1rem;font-size:1.05rem;color:var(--headings);border-bottom:1px solid var(--border);padding-bottom:.6rem;">Inventory Config</h3>
                            <div style="display:flex;flex-direction:column;gap:.9rem;">
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="color:var(--text-secondary);font-size:.9rem;">Min Stock</span>
                                    <span style="color:var(--text-primary);font-weight:600;">{{ $product->min_stock_level ?? '—' }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="color:var(--text-secondary);font-size:.9rem;">Max Stock</span>
                                    <span style="color:var(--text-primary);font-weight:600;">{{ $product->max_stock_level ?? '—' }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="color:var(--text-secondary);font-size:.9rem;">Reorder Point</span>
                                    <span style="color:var(--text-primary);font-weight:600;">{{ $product->reorder_point ?? '—' }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="color:var(--text-secondary);font-size:.9rem;">Reorder Qty</span>
                                    <span style="color:var(--text-primary);font-weight:600;">{{ $product->reorder_quantity ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── RIGHT COLUMN ─────────────────────────────── --}}
                <div style="display:flex;flex-direction:column;gap:1.5rem;">

                    {{-- Image Gallery --}}
                    <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                        @if($product->images && count($product->images) > 0)
                            {{-- Main preview image --}}
                            <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border);margin-bottom:1rem;aspect-ratio:1;background:var(--surface-secondary);">
                                <img id="mainImage" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;transition:opacity .2s;">
                            </div>
                            {{-- Thumbnail strip --}}
                            @if(count($product->images) > 1)
                                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(56px,1fr));gap:.5rem;">
                                    @foreach($product->images as $i => $img)
                                        <div onclick="document.getElementById('mainImage').src='{{ $product->imageUrl($i) }}'"
                                            style="border-radius:6px;overflow:hidden;border:2px solid {{ $i === 0 ? 'var(--primary)' : 'var(--border)' }};aspect-ratio:1;cursor:pointer;transition:border-color .2s;"
                                            onmouseover="this.style.borderColor='var(--primary)'"
                                            onmouseout="this.style.borderColor='{{ $i === 0 ? 'var(--primary)' : 'var(--border)' }}'">
                                            <img src="{{ $product->imageUrl($i) }}" style="width:100%;height:100%;object-fit:cover;">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div style="width:100%;height:200px;background:var(--surface-secondary);border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--text-secondary);">
                                <i class="fa-solid fa-image" style="font-size:2.5rem;margin-bottom:.75rem;opacity:.4;"></i>
                                <span style="font-size:.9rem;">No images uploaded</span>
                            </div>
                        @endif
                    </div>

                    {{-- Physical Details --}}
                    <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                        <h3 style="margin:0 0 1rem;font-size:1.05rem;color:var(--headings);border-bottom:1px solid var(--border);padding-bottom:.6rem;">Physical Details</h3>
                        <div style="display:flex;flex-direction:column;gap:.9rem;">
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-secondary);font-size:.9rem;">Unit Type</span>
                                <span style="color:var(--text-primary);font-weight:600;text-transform:capitalize;">{{ $product->unit_type ?: '—' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-secondary);font-size:.9rem;">Weight</span>
                                <span style="color:var(--text-primary);font-weight:600;">{{ $product->weight_kg ? $product->weight_kg . ' kg' : '—' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-secondary);font-size:.9rem;">Barcode</span>
                                <span style="color:var(--text-primary);font-family:monospace;font-weight:600;">{{ $product->barcode ?: '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Metadata --}}
                    <div class="synkra-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                        <h3 style="margin:0 0 1rem;font-size:1.05rem;color:var(--headings);border-bottom:1px solid var(--border);padding-bottom:.6rem;">Record Info</h3>
                        <div style="display:flex;flex-direction:column;gap:.75rem;font-size:.85rem;">
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-secondary);">Created</span>
                                <span style="color:var(--text-primary);">{{ $product->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-secondary);">Last Updated</span>
                                <span style="color:var(--text-primary);">{{ $product->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
