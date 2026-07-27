<x-layouts.app title="Edit Contract">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('supply_chain.contracts.show', $contract->id) }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Contract
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Edit Contract: {{ $contract->contract_number }}</h1>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('supply_chain.contracts.update', $contract->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1.5rem;">
                    @php
                        $supplierOptions = $suppliers->map(function($s) {
                            return ['value' => $s->id, 'label' => $s->company_name];
                        })->toArray();
                    @endphp
                    <x-ui.select name="supplier_id" label="Supplier" :options="$supplierOptions" required :selected="$contract->supplier_id" />
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <x-ui.input name="contract_number" label="Contract Number / ID" required value="{{ old('contract_number', $contract->contract_number) }}" />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <x-ui.input name="start_date" type="date" label="Start Date" required value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" />
                    <x-ui.input name="end_date" type="date" label="End Date (Optional)" value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}" />
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Contract Terms Summary</label>
                    <textarea name="terms" rows="4" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;" placeholder="Key terms, pricing agreements, etc...">{{ old('terms', is_array($contract->terms) ? implode("\n", $contract->terms) : $contract->terms) }}</textarea>
                </div>

                <div style="margin-bottom: 2rem; display: flex; align-items: center; gap: 10px;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $contract->is_active ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--primary);">
                    <label for="is_active" style="font-weight: 600; color: var(--headings);">Contract is Active</label>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('supply_chain.contracts.show', $contract->id) }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Update Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
    <style>
        .flowexa-select-group, .flowexa-input-group { max-width: none !important; }
    </style>
</x-layouts.app>
