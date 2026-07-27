<x-layouts.app title="Purchase Order Details">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <a href="{{ route('supply_chain.purchasing.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">PO #{{ $purchaseOrder->po_number }}</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Status: <span style="color: var(--primary); font-weight: 700; text-transform: uppercase;">{{ $purchaseOrder->status }}</span></p>
            </div>
            <div style="display: flex; gap: 1rem;">
                @if($purchaseOrder->status === 'draft')
                <form action="{{ route('supply_chain.purchasing.approve', $purchaseOrder->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flowexa-btn" style="background: #16a34a; border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(22,163,74,0.2);">
                        <i class="fa-solid fa-check-double"></i> Approve Order
                    </button>
                </form>
                @endif

                @if(in_array($purchaseOrder->status, ['draft', 'approved']))
                <form action="{{ route('supply_chain.purchasing.cancel', $purchaseOrder->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    <button type="submit" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--danger); color: var(--danger); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-ban"></i> Cancel Order
                    </button>
                </form>
                @endif

                <button class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-print"></i> Print PO
                </button>
                @if($purchaseOrder->status === 'draft')
                <a href="{{ route('supply_chain.purchasing.edit', $purchaseOrder->id) }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Order
                </a>
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Supplier</div>
                <div style="color: var(--headings); font-weight: 700;">{{ $purchaseOrder->supplier->company_name }}</div>
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.25rem;">{{ $purchaseOrder->supplier->contact_person }}</div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Order Dates</div>
                <div style="color: var(--headings); font-weight: 700;">Issued: {{ $purchaseOrder->order_date ? \Carbon\Carbon::parse($purchaseOrder->order_date)->format('M d, Y') : 'N/A' }}</div>
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.25rem;">Expected: {{ $purchaseOrder->expected_delivery_date ? \Carbon\Carbon::parse($purchaseOrder->expected_delivery_date)->format('M d, Y') : 'N/A' }}</div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Total Amount</div>
                <div style="color: var(--primary); font-size: 1.5rem; font-weight: 800;">₵{{ number_format($purchaseOrder->total_amount, 2) }}</div>
            </div>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="color: var(--headings); margin: 0; font-size: 1.1rem; font-weight: 700;">Order Items</h3>
                @if($purchaseOrder->status === 'draft')
                <button class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-plus"></i> Add Item
                </button>
                @endif
            </div>

            @if($purchaseOrder->items->isEmpty())
                <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                    <p>No items added to this purchase order yet.</p>
                </div>
            @else
                @php
                    $headers = ['Product', 'Qty Ordered', 'Unit Cost', 'Total'];
                    $rows = $purchaseOrder->items->map(function($item) {
                        return [
                            $item->product->name ?? 'Unknown Product',
                            $item->quantity_ordered,
                            '₵' . number_format($item->unit_cost, 2),
                            '₵' . number_format($item->total_cost, 2)
                        ];
                    })->toArray();
                @endphp
                <x-ui.table :headers="$headers" :rows="$rows" />
            @endif
        </div>

        @if($purchaseOrder->receivingReports->isNotEmpty())
        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
            <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Receiving History</h3>
            @php
                $rcvHeaders = ['Report #', 'Date', 'Received By', 'Status'];
                $rcvRows = $purchaseOrder->receivingReports->map(function($report) {
                    return [
                        $report->receipt_number,
                        \Carbon\Carbon::parse($report->received_date)->format('M d, Y'),
                        $report->receiver->name ?? 'N/A',
                        $report->status
                    ];
                })->toArray();
            @endphp
            <x-ui.table :headers="$rcvHeaders" :rows="$rcvRows" />
        </div>
        @endif
    </div>
</x-layouts.app>
