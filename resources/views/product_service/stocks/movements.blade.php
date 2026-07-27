<x-layouts.app title="Stock Movements">
    <x-ui.grid>
        @php
            $recordsMap = $movements->mapWithKeys(fn ($movement) => [
                $movement->id => [
                    'product' => $movement->product?->name,
                    'location' => $movement->location?->name,
                    'type' => $movement->movementType?->name ?? $movement->movement_type,
                    'quantity' => $movement->quantity,
                    'previous_balance' => $movement->previous_balance,
                    'new_balance' => $movement->new_balance,
                    'notes' => $movement->notes,
                    'created_by' => $movement->creator?->name,
                    'created_at' => $movement->created_at?->format('M d, Y h:i A'),
                ],
            ]);

            $headers = ['Date', 'Product', 'Type', 'Location', 'Qty', 'Actions'];
            $rows = $movements->map(function ($movement) {
                $actions = new \Illuminate\Support\HtmlString(
                    '<button type="button" class="flowexa-table-action-btn" title="View" onclick="openStockRecordModal('
                    . e(json_encode($movement->id)) . ', stockRecordsMap, ' . e(json_encode(route('product_service.stocks.receive.create'))) . ')">'
                    . '<i class="fa-solid fa-eye"></i></button>'
                );

                return [
                    $movement->created_at?->format('M d, Y h:i A'),
                    $movement->product?->name ?? '—',
                    $movement->movementType?->name ?? $movement->movement_type,
                    $movement->location?->name ?? '—',
                    $movement->quantity,
                    $actions,
                ];
            })->all();
        @endphp

        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:1200px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
                <div>
                    <h1 style="color:var(--headings);margin:0 0 .5rem 0;">Stock Movements</h1>
                    <p style="color:var(--text-secondary);margin:0;">Track all stock ins, outs, and adjustments.</p>
                </div>
                <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
                    <a href="{{ route('product_service.stocks.receive.create') }}" class="flowexa-btn" style="background:var(--primary);border:none;color:white;padding:.6rem 1.25rem;border-radius:8px;font-weight:600;text-decoration:none;">
                        <i class="fa-solid fa-plus"></i> Receive Stock
                    </a>
                    <a href="{{ route('product_service.stocks.issue.create') }}" class="flowexa-btn" style="background:var(--surface-secondary);border:1px solid var(--border);color:var(--text-primary);padding:.6rem 1.25rem;border-radius:8px;font-weight:600;text-decoration:none;">
                        Issue Stock
                    </a>
                </div>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:2rem;" />
            @endif

            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;padding:1.5rem;">
                @if($movements->isEmpty())
                    <div style="text-align:center;padding:4rem 2rem;">
                        <i class="fa-solid fa-arrows-rotate" style="font-size:2rem;color:var(--text-secondary);margin-bottom:1rem;"></i>
                        <h3 style="margin:0 0 .5rem 0;">No Movements Yet</h3>
                        <p style="color:var(--text-secondary);margin:0 0 1.5rem 0;">Receive or issue stock to create movement records.</p>
                        <a href="{{ route('product_service.stocks.receive.create') }}" style="background:var(--primary);color:white;padding:.6rem 1.5rem;border-radius:8px;font-weight:600;text-decoration:none;">Receive Stock</a>
                    </div>
                @else
                    <x-ui.filter-bar
                        searchPlaceholder="Search by product, type, location…"
                        :filters="[
                            ['name' => 'type', 'label' => 'Type', 'options' => [
                                ['value' => '', 'label' => 'All Types'],
                                ['value' => 'receive', 'label' => 'Receive'],
                                ['value' => 'issue', 'label' => 'Issue'],
                                ['value' => 'transfer', 'label' => 'Transfer'],
                                ['value' => 'adjustment', 'label' => 'Adjustment'],
                                ['value' => 'return', 'label' => 'Return'],
                            ]],
                        ]"
                    >
                        <a href="{{ route('product_service.export.stock_movements', ['format' => 'csv']) }}" class="flowexa-filter-btn">
                            <i class="fa-solid fa-file-export"></i> Export CSV
                        </a>
                        <a href="{{ route('product_service.export.stock_movements', ['format' => 'xlsx']) }}" class="flowexa-filter-btn">
                            <i class="fa-solid fa-file-excel"></i> Excel
                        </a>
                    </x-ui.filter-bar>
                    <x-ui.table :headers="$headers" :rows="$rows" />
                    <div style="padding:1rem;">{{ $movements->links() }}</div>
                @endif
            </div>
        </div>

        @include('product_service.stocks.partials.stock-record-modal')
        <script>const stockRecordsMap = @json($recordsMap);</script>
    </x-ui.grid>
</x-layouts.app>
