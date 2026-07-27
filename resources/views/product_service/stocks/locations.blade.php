<x-layouts.app title="Stock Locations">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:1200px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;">
                <div>
                    <h1 style="color:var(--headings);margin:0 0 .5rem 0;">Stock Locations</h1>
                    <p style="color:var(--text-secondary);margin:0;">Manage warehouses, stores, and other storage locations for your inventory.</p>
                </div>
                <button type="button" onclick="openflowexaModal('addLocationModal')"
                        class="flowexa-btn flowexa-btn-primary"
                        style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.6rem 1.25rem;border-radius:8px;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-plus"></i> Add Location
                </button>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:2rem;" />
            @endif
            @if($errors->any())
                <x-ui.alert type="danger" title="Error" :message="$errors->first()" style="margin-bottom:2rem;" />
            @endif

            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;padding:1.5rem;">
                @if($locations->isEmpty())
                    <div style="text-align:center;padding:4rem 2rem;">
                        <div style="width:64px;height:64px;background:var(--surface-secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem auto;font-size:1.5rem;color:var(--text-secondary);">
                            <i class="fa-solid fa-warehouse"></i>
                        </div>
                        <h3 style="margin:0 0 .5rem 0;color:var(--text-primary);">No Locations Yet</h3>
                        <p style="color:var(--text-secondary);margin:0 0 1.5rem 0;font-size:.95rem;">Add your first warehouse or store location to start tracking stock.</p>
                        <button type="button" onclick="openflowexaModal('addLocationModal')"
                                style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.6rem 1.5rem;border-radius:8px;font-weight:600;">
                            Add First Location
                        </button>
                    </div>
                @else
                    <x-ui.filter-bar
                        searchPlaceholder="Search locations by name, address…"
                        :filters="[
                            ['name' => 'type', 'label' => 'Type', 'options' => [
                                ['value' => '', 'label' => 'All Types'],
                                ['value' => 'warehouse', 'label' => 'Warehouse'],
                                ['value' => 'store', 'label' => 'Store'],
                                ['value' => 'retail', 'label' => 'Retail'],
                                ['value' => 'office', 'label' => 'Office'],
                                ['value' => 'other', 'label' => 'Other'],
                            ]],
                            ['name' => 'status', 'label' => 'Status', 'options' => [
                                ['value' => '', 'label' => 'All Status'],
                                ['value' => 'active', 'label' => 'Active'],
                                ['value' => 'inactive', 'label' => 'Inactive'],
                            ]],
                        ]"
                    >
                        <a href="{{ route('product_service.export.stock_locations', ['format' => 'csv']) }}" class="flowexa-filter-btn">
                            <i class="fa-solid fa-file-export"></i> Export
                        </a>
                    </x-ui.filter-bar>

                    @php
                        $headers = ['Name', 'Type', 'Address', 'Status', 'Actions'];
                        $rows = $locations->map(function ($location) {
                            $nameCell = '<div style="display:flex;align-items:center;gap:.5rem;"><strong style="color:var(--headings);">' . e($location->name) . '</strong>';
                            if ($location->is_default) {
                                $nameCell .= '<span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:.75rem;font-weight:700;background:rgba(249,115,22,.1);color:var(--primary);">Default</span>';
                            }
                            $nameCell .= '</div>';

                            $actions = new \Illuminate\Support\HtmlString(
                                '<div class="flowexa-table-actions" style="justify-content:flex-end;">'
                                . '<button type="button" class="flowexa-table-action-btn" title="Edit"'
                                . ' data-location-id="' . e($location->id) . '"'
                                . ' data-location-name="' . e($location->name) . '"'
                                . ' data-location-type="' . e($location->location_type) . '"'
                                . ' data-location-address="' . e($location->address ?? '') . '"'
                                . ' data-location-default="' . ($location->is_default ? '1' : '0') . '"'
                                . ' data-location-active="' . ($location->is_active ? '1' : '0') . '"'
                                . ' onclick="openEditLocationModal(this)"><i class="fa-solid fa-pen-to-square"></i></button>'
                                . '<form action="' . e(route('product_service.stocks.locations.destroy', $location->id)) . '" method="POST" onsubmit="return confirm(\'Delete this location?\');" style="margin:0;display:inline;">'
                                . csrf_field() . method_field('DELETE')
                                . '<button type="submit" class="flowexa-table-action-btn" style="color:var(--danger);" title="Delete"><i class="fa-solid fa-trash"></i></button>'
                                . '</form></div>'
                            );

                            return [
                                new \Illuminate\Support\HtmlString($nameCell),
                                ucfirst(str_replace('_', ' ', $location->location_type)),
                                $location->address ? \Illuminate\Support\Str::limit($location->address, 50) : '—',
                                $location->is_active ? 'Active' : 'Inactive',
                                $actions,
                            ];
                        })->all();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />
                @endif
            </div>
        </div>

        @php
            $locationTypeOptions = [
                'warehouse' => 'Warehouse',
                'store' => 'Store',
                'retail' => 'Retail',
                'office' => 'Office',
                'other' => 'Other',
            ];
        @endphp

        <style>#addLocationModal-trigger-btn, #editLocationModal-trigger-btn { display:none !important; }</style>

        <x-ui.modal id="addLocationModal" triggerId="addLocationModal-trigger-btn" title="Add Stock Location">
            <form id="addLocationForm" action="{{ route('product_service.stocks.locations.store') }}" method="POST">
                @csrf
                @include('product_service.stocks.partials.location-form-fields', ['locationTypeOptions' => $locationTypeOptions])
            </form>
            <x-slot:footer>
                <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('addLocationModal')">Cancel</button>
                <button type="button" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1rem;border-radius:8px;font-weight:600;" onclick="document.getElementById('addLocationForm').submit()">Save Location</button>
            </x-slot:footer>
        </x-ui.modal>

        <x-ui.modal id="editLocationModal" triggerId="editLocationModal-trigger-btn" title="Edit Stock Location">
            <form id="editLocationForm" method="POST">
                @csrf @method('PUT')
                @include('product_service.stocks.partials.location-form-fields', ['locationTypeOptions' => $locationTypeOptions, 'prefix' => 'edit_'])
            </form>
            <x-slot:footer>
                <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('editLocationModal')">Cancel</button>
                <button type="button" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1rem;border-radius:8px;font-weight:600;" onclick="document.getElementById('editLocationForm').submit()">Update Location</button>
            </x-slot:footer>
        </x-ui.modal>

        <script>
        function openEditLocationModal(btn) {
            const form = document.getElementById('editLocationForm');
            form.action = '{{ url('product-service/stocks/locations') }}/' + btn.dataset.locationId;
            form.querySelector('[name="name"]').value = btn.dataset.locationName;
            form.querySelector('[name="location_type"]').value = btn.dataset.locationType;
            form.querySelector('[name="address"]').value = btn.dataset.locationAddress || '';
            form.querySelector('[name="is_default"]').checked = btn.dataset.locationDefault === '1';
            form.querySelector('[name="is_active"]').checked = btn.dataset.locationActive === '1';
            openflowexaModal('editLocationModal');
        }
        </script>
    </x-ui.grid>
</x-layouts.app>
