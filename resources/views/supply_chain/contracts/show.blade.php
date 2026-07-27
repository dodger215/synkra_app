<x-layouts.app title="Contract Details">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1000px; margin: 0 auto;">
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <a href="{{ route('supply_chain.contracts.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Contracts
                </a>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Contract: {{ $contract->contract_number }}</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Partner: <a href="{{ route('supply_chain.suppliers.show', $contract->supplier_id) }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">{{ $contract->supplier->company_name }}</a></p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('supply_chain.contracts.edit', $contract->id) }}" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Contract
                </a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Validity Period</div>
                <div style="color: var(--headings); font-weight: 700;">
                    {{ $contract->start_date->format('M d, Y') }} -
                    {{ $contract->end_date ? $contract->end_date->format('M d, Y') : 'Indefinite' }}
                </div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Status</div>
                @php $isActive = $contract->is_active && (!$contract->end_date || $contract->end_date->isFuture()); @endphp
                <div style="color: {{ $isActive ? 'var(--success)' : 'var(--danger)' }}; font-weight: 800; text-transform: uppercase;">
                    {{ $isActive ? 'Active & Valid' : 'Inactive / Expired' }}
                </div>
            </div>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Terms & Conditions Summary</h3>
            <div style="color: var(--text-primary); line-height: 1.7; background: var(--surface-secondary); padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border);">
                @if(is_array($contract->terms))
                    {!! nl2br(e(implode("\n", $contract->terms))) !!}
                @else
                    {!! nl2br(e($contract->terms ?: 'No summary provided.')) !!}
                @endif
            </div>

            @if($contract->file_url)
            <div style="margin-top: 2rem;">
                <a href="{{ $contract->file_url }}" target="_blank" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-file-pdf"></i> View Full Document
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
