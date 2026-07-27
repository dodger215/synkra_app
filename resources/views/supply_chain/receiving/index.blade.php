<x-layouts.app title="Receiving Reports">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Receiving Reports</h1>
                <p style="color: var(--text-secondary); margin: 0;">Log and track incoming stock from purchase orders.</p>
            </div>
            <div>
                <a href="{{ route('supply_chain.receiving.create') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                    <i class="fa-solid fa-boxes-packing"></i> New Receipt
                </a>
            </div>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(34, 197, 94, 0.2); font-weight: 600;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; overflow: hidden;">
            @if($reports->isEmpty())
                <div style="text-align: center; padding: 4rem 0;">
                    <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); margin: 0 auto 1.5rem;">
                        <i class="fa-solid fa-clipboard-check" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 style="color: var(--headings); margin-bottom: 0.5rem;">No receiving reports</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Log items as they arrive to update your inventory.</p>
                    <a href="{{ route('supply_chain.receiving.create') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-block;">
                        Create Receipt Report
                    </a>
                </div>
            @else
                @php
                    $headers = ['Receipt #', 'PO Number', 'Date Received', 'Received By', 'Status', 'Actions'];
                    $rows = $reports->map(function($report) {
                        return [
                            $report->receiving_number,
                            $report->purchaseOrder->po_number ?? 'N/A',
                            $report->received_at ? \Carbon\Carbon::parse($report->received_at)->format('M d, Y') : 'N/A',
                            $report->receiver->name ?? 'N/A',
                            $report->status,
                            new \Illuminate\Support\HtmlString('
                                <div style="display: flex; gap: 10px;">
                                    <a href="' . route('supply_chain.receiving.show', $report->id) . '" style="color: var(--text-secondary);"><i class="fa-solid fa-eye"></i></a>
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
