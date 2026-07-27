<x-layouts.app title="Stock Counts">
    <x-ui.grid>
        @php
            $recordsMap = $counts->mapWithKeys(fn ($item) => [$item->id => [
                'count_number' => $item->count_number, 'product' => $item->product?->name,
                'location' => $item->location?->name, 'schedule' => $item->schedule?->name,
                'expected_quantity' => $item->expected_quantity, 'counted_quantity' => $item->counted_quantity,
                'variance' => $item->variance, 'status' => $item->status,
                'counted_by' => $item->counter?->name, 'notes' => $item->notes,
                'counted_at' => $item->counted_at?->format('M d, Y h:i A'),
            ]]);
            $headers = ['Number', 'Product', 'Location', 'Expected', 'Counted', 'Actions'];
            $rows = $counts->map(fn ($item) => [
                $item->count_number, $item->product?->name ?? '—', $item->location?->name ?? '—',
                $item->expected_quantity, $item->counted_quantity,
                new \Illuminate\Support\HtmlString('<button type="button" class="flowexa-table-action-btn" onclick="openStockRecordModal(' . e(json_encode($item->id)) . ', stockRecordsMap, ' . e(json_encode(route('product_service.stocks.count.create'))) . ')"><i class="fa-solid fa-eye"></i></button>'),
            ])->all();
        @endphp
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:1200px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
                <div><h1 style="color:var(--headings);margin:0 0 .5rem 0;">Stock Counts</h1><p style="color:var(--text-secondary);margin:0;">Physical inventory counts and variances.</p></div>
                <a href="{{ route('product_service.stocks.count.create') }}" style="background:var(--primary);color:white;padding:.6rem 1.25rem;border-radius:8px;font-weight:600;text-decoration:none;"><i class="fa-solid fa-plus"></i> New Count</a>
            </div>
            @if(session('success'))<x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:2rem;" />@endif
            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;padding:1.5rem;">
                @if($counts->isEmpty())
                    <div style="text-align:center;padding:4rem 2rem;"><h3>No Stock Counts</h3><p style="color:var(--text-secondary);margin:1rem 0 1.5rem;">Record a physical count to reconcile inventory.</p><a href="{{ route('product_service.stocks.count.create') }}" style="background:var(--primary);color:white;padding:.6rem 1.5rem;border-radius:8px;text-decoration:none;font-weight:600;">Record Count</a></div>
                @else
                    <x-ui.filter-bar
                        searchPlaceholder="Search by product, number, location…"
                        :filters="[
                            ['name' => 'status', 'label' => 'Status', 'options' => [
                                ['value' => '', 'label' => 'All Status'],
                                ['value' => 'pending', 'label' => 'Pending'],
                                ['value' => 'completed', 'label' => 'Completed'],
                                ['value' => 'approved', 'label' => 'Approved'],
                            ]],
                        ]"
                    >
                        <a href="{{ route('product_service.export.stock_counts', ['format' => 'csv']) }}" class="flowexa-filter-btn">
                            <i class="fa-solid fa-file-export"></i> Export CSV
                        </a>
                        <a href="{{ route('product_service.export.stock_counts', ['format' => 'xlsx']) }}" class="flowexa-filter-btn">
                            <i class="fa-solid fa-file-excel"></i> Excel
                        </a>
                    </x-ui.filter-bar>
                    <x-ui.table :headers="$headers" :rows="$rows" /><div style="padding:1rem;">{{ $counts->links() }}</div>
                @endif
            </div>
        </div>
        @include('product_service.stocks.partials.stock-record-modal')
        <script>const stockRecordsMap = @json($recordsMap);</script>
    </x-ui.grid>
</x-layouts.app>
