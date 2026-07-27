<x-layouts.app title="Add Contract">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('supply_chain.contracts.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Contracts
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Register New Contract</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Enter the details of your agreement with a supplier.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('supply_chain.contracts.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.5rem;">
                    @php
                        $supplierOptions = $suppliers->map(function($s) {
                            return ['value' => $s->id, 'label' => $s->company_name];
                        })->toArray();
                        array_unshift($supplierOptions, ['value' => '', 'label' => 'Select a supplier']);
                    @endphp
                    <x-ui.select name="supplier_id" label="Supplier" :options="$supplierOptions" required value="{{ old('supplier_id') }}" />
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <x-ui.input name="contract_number" label="Contract Number / ID" placeholder="e.g. AGR-2026-001" required value="{{ old('contract_number') }}" />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <x-ui.input name="start_date" type="date" label="Start Date" required value="{{ old('start_date', date('Y-m-d')) }}" />
                    <x-ui.input name="end_date" type="date" label="End Date (Optional)" value="{{ old('end_date') }}" />
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Contract Terms Summary</label>
                    <textarea name="terms" rows="4" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;" placeholder="Key terms, pricing agreements, etc...">{{ old('terms') }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('supply_chain.contracts.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                        Save Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
    <style>
        .flowexa-select-group, .flowexa-input-group { max-width: none !important; }
    </style>
</x-layouts.app>
