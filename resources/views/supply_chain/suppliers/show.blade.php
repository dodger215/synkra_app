<x-layouts.app title="Supplier Details">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <a href="{{ route('supply_chain.suppliers.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Suppliers
                </a>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">{{ $supplier->company_name }}</h1>
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 0.5rem;">
                    <p style="color: var(--text-secondary); margin: 0;">Supplier Code: <span style="color: var(--headings); font-weight: 700;">{{ $supplier->supplier_code }}</span></p>

                    @if($supplier->connection_status === 'pending')
                        <span class="flowexa-badge-pill" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 20px; text-transform: uppercase;">Pending Approval</span>
                    @elseif($supplier->connection_status === 'approved')
                        <span class="flowexa-badge-pill" style="background: rgba(34, 197, 94, 0.1); color: #22c55e; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 20px; text-transform: uppercase;">Approved Partner</span>
                    @elseif($supplier->connection_status === 'rejected')
                        <span class="flowexa-badge-pill" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 20px; text-transform: uppercase;">Rejected Request</span>
                    @endif
                </div>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('supply_chain.suppliers.edit', $supplier->id) }}" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Supplier
                </a>
            </div>
        </div>

        @if($supplier->connection_status === 'rejected' && $supplier->rejection_reason)
            <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); padding: 1.5rem; border-radius: 16px; margin-bottom: 2rem;">
                <h4 style="color: #991b1b; margin: 0 0 0.5rem 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-exclamation"></i> Rejection Reason
                </h4>
                <p style="color: #991b1b; margin: 0; font-size: 0.95rem;">{{ $supplier->rejection_reason }}</p>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            {{-- Contact Info Card --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Contact Information</h3>

                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Contact Person</div>
                            <div style="color: var(--headings); font-weight: 600;">{{ $supplier->contact_person ?? 'Not specified' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Email Address</div>
                            <div style="color: var(--headings); font-weight: 600;">{{ $supplier->email ?? 'Not specified' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Phone Number</div>
                            <div style="color: var(--headings); font-weight: 600;">{{ $supplier->phone ?? 'Not specified' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Address</div>
                            <div style="color: var(--headings); font-weight: 600; line-height: 1.5;">{!! nl2br(e($supplier->address)) ?: 'Not specified' !!}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity / History Column --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 style="color: var(--headings); margin: 0; font-size: 1.1rem; font-weight: 700;">Linked Products & Reorder Alerts</h3>
                        <button onclick="document.getElementById('linkProductModal').style.display='flex'" class="flowexa-btn" style="background: var(--primary); color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                            <i class="fa-solid fa-plus"></i> Link Product
                        </button>
                    </div>

                    @if($supplier->products->isEmpty())
                        <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                            <i class="fa-solid fa-boxes-stacked" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                            <p>No products linked to this supplier.</p>
                        </div>
                    @else
                        @php
                            $pHeaders = ['Product', 'SKU', 'Reorder Point', 'Active Alerts'];
                            $pRows = $supplier->products->map(function($product) {
                                return [
                                    $product->name,
                                    $product->sku,
                                    $product->reorder_point,
                                    $product->reorderAlerts->where('status', 'active')->count() ?: '0'
                                ];
                            })->toArray();
                        @endphp
                        <x-ui.table :headers="$pHeaders" :rows="$pRows" />
                    @endif
                </div>

                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Recent Purchase Orders</h3>

                    @if($supplier->purchaseOrders->isEmpty())
                        <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                            <i class="fa-solid fa-file-invoice" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                            <p>No purchase orders found for this supplier.</p>
                        </div>
                    @else
                        @php
                            $headers = ['PO #', 'Date', 'Status', 'Total', 'Actions'];
                            $rows = $supplier->purchaseOrders->take(10)->map(function($po) {
                                return [
                                    $po->po_number,
                                    $po->order_date->format('M d, Y'),
                                    $po->status, // x-ui.table handles badge-like logic if we pass strings like 'active' but 'status' might be 'draft', 'sent', etc.
                                    '₵' . number_format($po->total_amount, 2),
                                    new \Illuminate\Support\HtmlString('<a href="' . route('supply_chain.purchasing.show', $po->id) . '" style="color: var(--primary); font-weight: 700; text-decoration: none;">View</a>')
                                ];
                            })->toArray();
                        @endphp
                        <x-ui.table :headers="$headers" :rows="$rows" />
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Link Product Modal --}}
    <div id="linkProductModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: var(--surface); border-radius: 24px; max-width: 500px; width: 100%; border: 1px solid var(--border); box-shadow: 0 20px 50px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; color: var(--headings); font-weight: 800;">Link Product to Supplier</h3>
                <button onclick="document.getElementById('linkProductModal').style.display='none'" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 1.25rem;"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('supply_chain.suppliers.link_product', $supplier->id) }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Select Product</label>
                    <select name="product_id" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;">
                        <option value="">-- Choose a product --</option>
                        @foreach($unlinkedProducts as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('linkProductModal').style.display='none'" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer;">Cancel</button>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Link Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
