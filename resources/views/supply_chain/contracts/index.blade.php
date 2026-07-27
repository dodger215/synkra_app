<x-layouts.app title="Supplier Contracts">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Supplier Contracts</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manage long-term agreements and vendor compliance.</p>
            </div>
            <div>
                <a href="{{ route('supply_chain.contracts.create') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                    <i class="fa-solid fa-file-contract"></i> Add Contract
                </a>
            </div>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(34, 197, 94, 0.2); font-weight: 600;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; overflow: hidden;">
            @if($contracts->isEmpty())
                <div style="text-align: center; padding: 4rem 0;">
                    <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); margin: 0 auto 1.5rem;">
                        <i class="fa-solid fa-file-signature" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 style="color: var(--headings); margin-bottom: 0.5rem;">No active contracts</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Upload and track your supplier agreements here.</p>
                    <a href="{{ route('supply_chain.contracts.create') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-block;">
                        Register Contract
                    </a>
                </div>
            @else
                @php
                    $headers = ['Contract #', 'Supplier', 'Start Date', 'End Date', 'Status', 'Actions'];
                    $rows = $contracts->map(function($contract) {
                        $isActive = $contract->is_active && (!$contract->end_date || $contract->end_date->isFuture());
                        return [
                            $contract->contract_number,
                            $contract->supplier->company_name ?? 'N/A',
                            $contract->start_date ? $contract->start_date->format('M d, Y') : 'N/A',
                            $contract->end_date ? $contract->end_date->format('M d, Y') : 'Indefinite',
                            new \Illuminate\Support\HtmlString('<span class="flowexa-badge-pill ' . ($isActive ? 'flowexa-badge-success' : 'flowexa-badge-danger') . '">' . ($isActive ? 'Active' : 'Expired/Inactive') . '</span>'),
                            new \Illuminate\Support\HtmlString('
                                <div style="display: flex; gap: 10px;">
                                    <a href="' . route('supply_chain.contracts.show', $contract->id) . '" style="color: var(--text-secondary);"><i class="fa-solid fa-eye"></i></a>
                                    <a href="' . route('supply_chain.contracts.edit', $contract->id) . '" style="color: var(--primary);"><i class="fa-solid fa-pen-to-square"></i></a>
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
