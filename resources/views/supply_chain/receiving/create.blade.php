<x-layouts.app title="New Receiving Report">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('supply_chain.receiving.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Reports
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">New Receiving Report</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Link this report to an open Purchase Order.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('supply_chain.receiving.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.5rem;">
                    @php
                        $poOptions = $purchaseOrders->map(function($po) {
                            return ['value' => $po->id, 'label' => $po->po_number . ' - ' . ($po->supplier->company_name ?? 'Unknown Supplier')];
                        })->toArray();
                        array_unshift($poOptions, ['value' => '', 'label' => 'Select a Purchase Order']);
                    @endphp
                    <x-ui.select name="po_id" label="Purchase Order" :options="$poOptions" required value="{{ old('po_id') }}" />
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <x-ui.input name="received_at" type="date" label="Date Received" required value="{{ old('received_at', date('Y-m-d')) }}" />
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Receiving Notes</label>
                    <textarea name="notes" rows="3" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;" placeholder="Condition of goods, discrepancies, etc...">{{ old('notes') }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('supply_chain.receiving.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                        Create Report
                    </button>
                </div>
            </form>
        </div>
    </div>
    <style>
        .flowexa-select-group, .flowexa-input-group { max-width: none !important; }
    </style>
</x-layouts.app>
