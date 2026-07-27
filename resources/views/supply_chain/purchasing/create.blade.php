<x-layouts.app title="Create Purchase Order">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('supply_chain.purchasing.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Create Purchase Order</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Initiate a new procurement request.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('supply_chain.purchasing.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.5rem;">
                    @php
                        $supplierOptions = $suppliers->map(function($s) {
                            return ['value' => $s->id, 'label' => $s->company_name . ' (' . $s->supplier_code . ')'];
                        })->toArray();
                        array_unshift($supplierOptions, ['value' => '', 'label' => 'Select a supplier']);
                    @endphp
                    <x-ui.select name="supplier_id" label="Supplier" :options="$supplierOptions" required value="{{ old('supplier_id') }}" />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <x-ui.input name="order_date" type="date" label="Order Date" required value="{{ old('order_date', date('Y-m-d')) }}" />
                    <x-ui.input name="expected_delivery_date" type="date" label="Expected Delivery" value="{{ old('expected_delivery_date') }}" />
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Internal Notes</label>
                    <textarea name="notes" rows="3" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;" placeholder="Special instructions or notes for the supplier...">{{ old('notes') }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('supply_chain.purchasing.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                        Create Purchase Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .flowexa-select-group, .flowexa-input-group { max-width: none !important; }
    </style>
</x-layouts.app>
