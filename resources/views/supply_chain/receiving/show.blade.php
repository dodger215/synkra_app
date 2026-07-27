<x-layouts.app title="Receiving Report Details">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <a href="{{ route('supply_chain.receiving.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Reports
                </a>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Receipt #{{ $report->receiving_number }}</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Linked to PO: <a href="{{ route('supply_chain.purchasing.show', $report->po_id) }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">#{{ $report->purchaseOrder->po_number }}</a></p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-print"></i> Print Receipt
                </button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Received Date</div>
                <div style="color: var(--headings); font-weight: 700;">{{ $report->received_at ? \Carbon\Carbon::parse($report->received_at)->format('M d, Y') : 'N/A' }}</div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Received By</div>
                <div style="color: var(--headings); font-weight: 700;">{{ $report->receiver->name ?? 'N/A' }}</div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Status</div>
                <div style="color: var(--success); font-weight: 800; text-transform: uppercase;">{{ $report->status }}</div>
            </div>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
            <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Received Items</h3>

            @if($report->purchaseOrder->items->isEmpty())
                <p style="color: var(--text-secondary); text-align: center; padding: 2rem;">No items listed on the linked PO.</p>
            @else
                @php
                    $headers = ['Product', 'Ordered Qty', 'Received Qty', 'Status'];
                    $rows = $report->purchaseOrder->items->map(function($item) {
                        return [
                            $item->product->name ?? 'Unknown Product',
                            $item->quantity_ordered,
                            $item->quantity_received ?? 0,
                            new \Illuminate\Support\HtmlString('<span style="color: var(--text-secondary); font-weight: 600;">' . (($item->quantity_received >= $item->quantity_ordered) ? 'Fully Received' : 'Pending/Partial') . '</span>')
                        ];
                    })->toArray();
                @endphp
                <x-ui.table :headers="$headers" :rows="$rows" />
            @endif
        </div>
    </div>
</x-layouts.app>
