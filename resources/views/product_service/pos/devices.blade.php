<x-layouts.app title="POS Devices">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Hardware Management</h1>
                    <p style="color: var(--text-secondary); margin: 0;">Configure and monitor your POS terminals and printers.</p>
                </div>
                <button onclick="openflowexaModal('addDeviceModal')" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-plus"></i> Add New Device
                </button>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
                @foreach($devices as $device)
                    <div class="flowexa-card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 1.75rem; transition: transform 0.2s; position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; right: 0; padding: 1rem;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 99px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; background: {{ $device->status === 'online' ? 'rgba(22,163,74,0.1)' : 'rgba(100,116,139,0.1)' }}; color: {{ $device->status === 'online' ? '#16a34a' : '#64748b' }};">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
                                {{ $device->status }}
                            </span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem;">
                            <div style="width: 56px; height: 56px; background: var(--surface-secondary); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--text-secondary); border: 1px solid var(--border);">
                                <i class="fa-solid fa-print"></i>
                            </div>
                            <div>
                                <h3 style="margin: 0; color: var(--headings); font-size: 1.1rem; font-weight: 800;">{{ $device->device_name }}</h3>
                                <p style="margin: 0.25rem 0 0; color: var(--text-secondary); font-size: 0.85rem;">{{ $device->location ? $device->location->name : 'No Assigned Location' }}</p>
                            </div>
                        </div>

                        <div style="background: var(--surface-secondary); border-radius: 16px; padding: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">IP Address</div>
                                <div style="font-size: 0.9rem; font-weight: 600; color: var(--headings);">{{ $device->ip_address ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Interface</div>
                                <div style="font-size: 0.9rem; font-weight: 600; color: var(--headings);">{{ ucfirst($device->connection_type) }}</div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.75rem;">
                            <form action="{{ route('product_service.pos.device.test-print') }}" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="pos_device_id" value="{{ $device->id }}">
                                <button type="submit" class="flowexa-btn" style="width: 100%; padding: 0.6rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Test Print</button>
                            </form>
                            <form action="{{ route('product_service.pos.device.open-drawer') }}" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="pos_device_id" value="{{ $device->id }}">
                                <button type="submit" class="flowexa-btn" style="width: 100%; padding: 0.6rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Open Drawer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Add Device Modal --}}
        <x-ui.modal id="addDeviceModal" title="Register New POS Device">
            <form action="{{ route('product_service.pos.device.store') }}" method="POST" id="addDeviceForm">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <x-ui.input label="Device Name" name="device_name" placeholder="e.g. Counter 1 Printer" required="true" />

                    <x-ui.select label="Assigned Location" name="location_id" :options="$locations->map(fn($l) => ['value' => $l->id, 'label' => $l->name])->toArray()" />

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <x-ui.select label="Connection Type" name="connection_type" id="connectionTypeSelect" :options="[['value' => 'network', 'label' => 'Network (IP)'], ['value' => 'usb', 'label' => 'USB / Serial']]" required="true" />
                        <x-ui.input label="Serial Number (Optional)" name="serial_number" placeholder="SN-12345678" />
                    </div>

                    <div id="networkFields" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                        <x-ui.input label="IP Address" name="ip_address" placeholder="192.168.1.100" />
                        <x-ui.input label="Port" name="port" type="number" value="9100" />
                    </div>
                </div>
            </form>
            <x-slot:footer>
                <button type="button" class="flowexa-btn" onclick="closeflowexaModal('addDeviceModal')" style="padding: 0.75rem 1.5rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 12px; cursor: pointer; font-weight: 600;">Cancel</button>
                <button type="button" class="flowexa-btn" onclick="document.getElementById('addDeviceForm').submit()" style="padding: 0.75rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">Register Device</button>
            </x-slot:footer>
        </x-ui.modal>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const connType = document.getElementById('connectionTypeSelect');
                const networkFields = document.getElementById('networkFields');

                if (connType && networkFields) {
                    connType.addEventListener('change', function() {
                        networkFields.style.display = this.value === 'network' ? 'grid' : 'none';
                    });
                }
            });
        </script>
    </x-ui.grid>
</x-layouts.app>
