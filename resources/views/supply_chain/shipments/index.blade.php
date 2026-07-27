<x-layouts.app title="Incoming Shipments">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Shipment Tracking</h1>
            <p style="color: var(--text-secondary); margin: 0;">Monitor active inbound deliveries from your suppliers.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; overflow: hidden;">
            @if($shipments->isEmpty())
                <div style="text-align: center; padding: 4rem 0;">
                    <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); margin: 0 auto 1.5rem;">
                        <i class="fa-solid fa-truck-ramp-box" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 style="color: var(--headings); margin-bottom: 0.5rem;">No active shipments</h3>
                    <p style="color: var(--text-secondary);">Shipments will appear here once purchase orders are approved.</p>
                </div>
            @else
                @php
                    $headers = ['PO #', 'Supplier', 'Expected Date', 'Delivery Status', 'Actions'];
                    $rows = $shipments->map(function($po) {
                        return [
                            $po->po_number,
                            $po->supplier->company_name ?? 'N/A',
                            $po->expected_delivery_date ? $po->expected_delivery_date->format('M d, Y') : 'N/A',
                            new \Illuminate\Support\HtmlString('<span class="flowexa-badge-pill" style="background: var(--surface-secondary); color: var(--text-primary); text-transform: uppercase;">' . ($po->delivery_status ?? 'Pending') . '</span>'),
                            new \Illuminate\Support\HtmlString('
                                <div style="display: flex; gap: 10px;">
                                    <a href="' . route('supply_chain.shipments.show', $po->id) . '" style="color: var(--primary); font-weight: 700; text-decoration: none;">Track & Details</a>
                                </div>
                            ')
                        ];
                    })->toArray();
                @endphp
                <x-ui.table :headers="$headers" :rows="$rows" />
            @endif
        </div>
    </div>
</x-layouts.app>
