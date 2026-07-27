<x-layouts.app title="Stock Returns">
    <x-ui.grid>
        @php
            $recordsMap = $returns->mapWithKeys(fn ($item) => [$item->id => [
                'return_number' => $item->return_number, 'product' => $item->product?->name,
                'location' => $item->location?->name, 'quantity' => $item->quantity,
                'return_reason' => $item->return_reason, 'condition' => $item->condition,
                'status' => $item->status, 'created_by' => $item->creator?->name,
                'created_at' => $item->created_at?->format('M d, Y h:i A'),
            ]]);
            $headers = ['Number', 'Product', 'Location', 'Qty', 'Status', 'Actions'];
            $rows = $returns->map(fn ($item) => [
                $item->return_number, $item->product?->name ?? '—', $item->location?->name ?? '—',
                $item->quantity, $item->status,
                new \Illuminate\Support\HtmlString('<button type="button" class="flowexa-table-action-btn" onclick="openStockRecordModal(' . e(json_encode($item->id)) . ', stockRecordsMap, ' . e(json_encode(route('product_service.stocks.return.create'))) . ')"><i class="fa-solid fa-eye"></i></button>'),
            ])->all();
        @endphp
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:1200px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
                <div><h1 style="color:var(--headings);margin:0 0 .5rem 0;">Stock Returns</h1><p style="color:var(--text-secondary);margin:0;">Customer and supplier return records.</p></div>
                <a href="{{ route('product_service.stocks.return.create') }}" style="background:var(--primary);color:white;padding:.6rem 1.25rem;border-radius:8px;font-weight:600;text-decoration:none;"><i class="fa-solid fa-plus"></i> New Return</a>
            </div>
            @if(session('success'))<x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:2rem;" />@endif
            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;padding:1.5rem;">
                @if($returns->isEmpty())
                    <div style="text-align:center;padding:4rem 2rem;"><h3>No Returns Yet</h3><p style="color:var(--text-secondary);margin:1rem 0 1.5rem;">Log returned stock for inspection and restocking.</p><a href="{{ route('product_service.stocks.return.create') }}" style="background:var(--primary);color:white;padding:.6rem 1.5rem;border-radius:8px;text-decoration:none;font-weight:600;">Record Return</a></div>
                @else
                    <x-ui.filter-bar
                        searchPlaceholder="Search by product, number, reason…"
                        :filters="[
                            ['name' => 'status', 'label' => 'Status', 'options' => [
                                ['value' => '', 'label' => 'All Status'],
                                ['value' => 'pending', 'label' => 'Pending'],
                                ['value' => 'inspected', 'label' => 'Inspected'],
                                ['value' => 'restocked', 'label' => 'Restocked'],
                                ['value' => 'disposed', 'label' => 'Disposed'],
                            ]],
                        ]"
                    >
                        <a href="{{ route('product_service.export.stock_returns', ['format' => 'csv']) }}" class="flowexa-filter-btn">
                            <i class="fa-solid fa-file-export"></i> Export CSV
                        </a>
                        <a href="{{ route('product_service.export.stock_returns', ['format' => 'xlsx']) }}" class="flowexa-filter-btn">
                            <i class="fa-solid fa-file-excel"></i> Excel
                        </a>
                    </x-ui.filter-bar>
                    <x-ui.table :headers="$headers" :rows="$rows" /><div style="padding:1rem;">{{ $returns->links() }}</div>
                @endif
            </div>
        </div>
        @include('product_service.stocks.partials.stock-record-modal')
        <script>const stockRecordsMap = @json($recordsMap);</script>
    </x-ui.grid>
</x-layouts.app>
