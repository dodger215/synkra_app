<x-layouts.app title="Stock Bins">
    <x-ui.grid>
        @php
            $recordsMap = $bins->mapWithKeys(fn ($bin) => [$bin->id => [
                'bin_code' => $bin->bin_code, 'bin_type' => $bin->bin_type,
                'location' => $bin->location?->name, 'is_active' => $bin->is_active ? 'Active' : 'Inactive',
            ]]);
            $headers = ['Code', 'Type', 'Location', 'Status', 'Actions'];
            $rows = $bins->map(fn ($bin) => [
                $bin->bin_code ?? '—', $bin->bin_type ?? '—', $bin->location?->name ?? '—',
                $bin->is_active ? 'Active' : 'Inactive',
                new \Illuminate\Support\HtmlString('<button type="button" class="flowexa-table-action-btn" onclick="openStockRecordModal(' . e(json_encode($bin->id)) . ', stockRecordsMap)"><i class="fa-solid fa-eye"></i></button>'),
            ])->all();
        @endphp
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:1200px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
                <div><h1 style="color:var(--headings);margin:0 0 .5rem 0;">Stock Bins</h1><p style="color:var(--text-secondary);margin:0;">Manage storage bins within locations.</p></div>
                <button type="button" onclick="openflowexaModal('addBinModal')" style="background:var(--primary);color:white;padding:.6rem 1.25rem;border-radius:8px;font-weight:600;border:none;cursor:pointer;"><i class="fa-solid fa-plus"></i> Add Bin</button>
            </div>
            @if(session('success'))<x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:2rem;" />@endif
            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;">
                @if($bins->isEmpty())
                    <div style="text-align:center;padding:4rem 2rem;"><h3>No Bins Yet</h3><p style="color:var(--text-secondary);margin:1rem 0 1.5rem;">Create bins to organize stock within locations.</p><button type="button" onclick="openflowexaModal('addBinModal')" style="background:var(--primary);color:white;padding:.6rem 1.5rem;border-radius:8px;border:none;font-weight:600;cursor:pointer;">Add Bin</button></div>
                @else
                    <x-ui.table :headers="$headers" :rows="$rows" />
                @endif
            </div>
        </div>

        <style>#addBinModal-trigger-btn { display:none !important; }</style>
        <x-ui.modal id="addBinModal" triggerId="addBinModal-trigger-btn" title="Add Stock Bin">
            <form id="addBinForm" action="{{ route('product_service.stocks.bins.store') }}" method="POST">@csrf
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <label style="font-size:.85rem;font-weight:600;display:block;margin-bottom:.4rem;">Location *</label>
                        <select name="location_id" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                            <option value="">Select location</option>
                            @foreach($locations as $location)<option value="{{ $location->id }}">{{ $location->name }}</option>@endforeach
                        </select>
                    </div>
                    <x-ui.input name="bin_code" label="Bin Code" placeholder="e.g. A-01" required />
                    <x-ui.input name="bin_type" label="Bin Type" placeholder="e.g. shelf, pallet" />
                </div>
            </form>
            <x-slot:footer>
                <button type="button" onclick="closeflowexaModal('addBinModal')" style="padding:.5rem 1rem;border:1px solid var(--border);border-radius:8px;background:transparent;cursor:pointer;">Cancel</button>
                <button type="button" onclick="document.getElementById('addBinForm').submit()" style="padding:.5rem 1rem;border:none;border-radius:8px;background:var(--primary);color:white;font-weight:600;cursor:pointer;">Save Bin</button>
            </x-slot:footer>
        </x-ui.modal>

        @include('product_service.stocks.partials.stock-record-modal')
        <script>const stockRecordsMap = @json($recordsMap);</script>
    </x-ui.grid>
</x-layouts.app>
