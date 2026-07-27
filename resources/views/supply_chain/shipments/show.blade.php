<x-layouts.app title="Shipment Details">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1000px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('supply_chain.shipments.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Shipments
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Shipment Tracking: {{ $shipment->po_number }}</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Inbound from {{ $shipment->supplier->company_name }}</p>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                {{-- Tracking Status --}}
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Delivery Progress</h3>

                    @php
                        $statuses = ['Pending', 'In Transit', 'Out for Delivery', 'Delivered', 'Delayed'];
                        $currentStatus = $shipment->delivery_status ?? 'Pending';
                    @endphp

                    <div style="display: flex; flex-direction: column; gap: 2rem; position: relative; padding-left: 2rem;">
                        <div style="position: absolute; left: 7px; top: 0; bottom: 0; width: 2px; background: var(--border);"></div>

                        @foreach($statuses as $status)
                            @php
                                $isCompleted = ($status === $currentStatus || (array_search($status, $statuses) < array_search($currentStatus, $statuses)));
                                $isCurrent = ($status === $currentStatus);
                            @endphp
                            <div style="display: flex; align-items: center; gap: 1.5rem; position: relative;">
                                <div style="position: absolute; left: -25px; width: 16px; height: 16px; border-radius: 50%; background: {{ $isCompleted ? 'var(--primary)' : 'var(--surface)' }}; border: 2px solid {{ $isCompleted ? 'var(--primary)' : 'var(--border)' }}; z-index: 2;"></div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: {{ $isCurrent ? 'var(--primary)' : 'var(--headings)' }}; opacity: {{ $isCompleted || $isCurrent ? 1 : 0.5 }};">
                                        {{ $status }}
                                    </div>
                                    @if($isCurrent)
                                        <div style="font-size: 0.8rem; color: var(--text-secondary);">Last updated: {{ $shipment->updated_at->diffForHumans() }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Shipment Items --}}
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Items in this Shipment</h3>
                    @php
                        $headers = ['Product', 'Qty Ordered', 'Qty Received'];
                        $rows = $shipment->items->map(function($item) {
                            return [
                                $item->product->name ?? 'Unknown',
                                $item->quantity_ordered,
                                $item->quantity_received ?? 0
                            ];
                        })->toArray();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                {{-- Quick Actions --}}
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Update Status</h3>
                    <form action="{{ route('supply_chain.shipments.update-status', $shipment->id) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 1.5rem;">
                            @php
                                $statusOptions = collect($statuses)->map(fn($s) => ['value' => $s, 'label' => $s])->toArray();
                            @endphp
                            <x-ui.select name="delivery_status" label="New Status" :options="$statusOptions" :selected="$currentStatus" />
                        </div>
                        <button type="submit" class="flowexa-btn" style="width: 100%; background: var(--primary); border: none; color: white; padding: 0.75rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                            Update Shipment
                        </button>
                    </form>
                </div>

                {{-- Receiving Reports --}}
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.25rem 0; font-size: 1rem; font-weight: 700;">Receipts</h3>
                    @forelse($shipment->receivingReports as $report)
                        <div style="padding: 0.75rem; border-bottom: 1px solid var(--border);">
                            <a href="{{ route('supply_chain.receiving.show', $report->id) }}" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.9rem;">#{{ $report->receipt_number }}</a>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $report->created_at->format('M d, Y') }}</div>
                        </div>
                    @empty
                        <p style="font-size: 0.85rem; color: var(--text-secondary); text-align: center;">No receipts logged yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <style>
        .flowexa-select-group { max-width: none !important; }
    </style>
</x-layouts.app>
