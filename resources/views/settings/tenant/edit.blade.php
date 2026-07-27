<x-layouts.app title="Workspace Settings">
    <x-slot:head>
        <meta name="description" content="Update your company workspace settings.">
    </x-slot:head>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.0.2/dist/maplibre-gl.css" />
    <style>
        .location-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .location-grid { grid-template-columns: 1fr; }
        }
        .maplibregl-ctrl-logo, .maplibregl-ctrl-attrib { display: none !important; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/maplibre-gl@4.0.2/dist/maplibre-gl.js"></script>
    <script>
        let settingsMap, settingsMarker;
        const apiKey = '7a8ebe56abcf44e0be0c71f31d2fdfd7';

        function initSettingsMap() {
            const lat = {{ $tenant->latitude ?? 5.6037 }};
            const lng = {{ $tenant->longitude ?? -0.1870 }};
            const center = [lng, lat];

            settingsMap = new maplibregl.Map({
                container: 'settings-map',
                style: `https://maps.geoapify.com/v1/styles/osm-bright/style.json?apiKey=${apiKey}`,
                center: center,
                zoom: 15
            });

            settingsMarker = new maplibregl.Marker({ draggable: true })
                .setLngLat(center)
                .addTo(settingsMap);

            settingsMarker.on('dragend', function() {
                const pos = settingsMarker.getLngLat();
                document.getElementById('settings-lat').value = pos.lat;
                document.getElementById('settings-lng').value = pos.lng;
            });
        }

        async function searchSettingsLocation() {
            const city = document.getElementById('settings-city').value;
            const address = document.getElementById('settings-address').value;
            const query = `${address} ${city}`.trim();

            if (!query) return;

            try {
                const response = await fetch(`https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(query)}&apiKey=${apiKey}`);
                const data = await response.json();
                if (data.features && data.features.length > 0) {
                    const coords = data.features[0].geometry.coordinates; // [lng, lat]
                    settingsMap.setCenter(coords);
                    settingsMap.setZoom(15);
                    settingsMarker.setLngLat(coords);
                    document.getElementById('settings-lat').value = coords[1];
                    document.getElementById('settings-lng').value = coords[0];
                }
            } catch (error) {
                console.error('Geocoding error:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initSettingsMap();
            document.getElementById('settings-city').addEventListener('change', searchSettingsLocation);
            document.getElementById('settings-address').addEventListener('change', searchSettingsLocation);
        });
    </script>
    @endpush

    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Workspace Settings</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manage your company details and global workspace configuration.</p>
            </div>
            <a href="{{ url('invites') }}" class="flowexa-btn" style="background: rgba(59, 130, 246, 0.1); color: var(--primary); padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(59, 130, 246, 0.2); transition: all 0.2s;" onmouseover="this.style.background='var(--primary)'; this.style.color='white';" onmouseout="this.style.background='rgba(59, 130, 246, 0.1)'; this.style.color='var(--primary)';">
                <i class="fa-solid fa-users"></i> Manage Users
            </a>
        </div>

        @if(session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 2rem;" />
        @endif

        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="Please fix the errors below." style="margin-bottom: 2rem;">
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <div class="flowexa-card" style="padding: 2rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border);">
            <form action="{{ route('settings.workspace.update') }}" method="POST">
                @csrf

                <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Company Information</h3>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <x-ui.input name="name" label="Workspace Name" placeholder="e.g. Acme Corp" value="{{ old('name', $tenant->name) }}" required />

                    <x-ui.input type="tel" name="phone_number" label="Primary Contact Phone" placeholder="e.g. 0241234567" value="{{ old('phone_number', auth()->user()->phone_number) }}" icon="fa-solid fa-phone" required minlength="10" maxlength="15" pattern="\+?[0-9]{10,15}" title="Please enter a valid phone number (10-15 digits)" />

                    <div>
                        <x-ui.input name="subdomain" label="Custom Subdomain (Optional)" placeholder="acme" value="{{ old('subdomain', $tenant->subdomain) }}" />
                        <p style="margin: 0.5rem 0 0 0; font-size: 0.8rem; color: var(--text-secondary);">If set, you can access your workspace at <strong style="color: var(--primary);">subdomain.flowexa.test</strong></p>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; color: var(--headings); margin-bottom: 1rem;">Shop Banner Image</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                            @foreach($bannerOptions as $option)
                                <label style="position: relative; cursor: pointer; border-radius: 12px; overflow: hidden; border: 2px solid {{ ($tenant->settings['banner_url'] ?? '') === $option ? 'var(--primary)' : 'transparent' }}; transition: all 0.2s; aspect-ratio: 16/9;">
                                    <input type="radio" name="banner_url" value="{{ $option }}" style="display: none;" {{ ($tenant->settings['banner_url'] ?? '') === $option ? 'checked' : '' }} onchange="this.parentElement.parentElement.querySelectorAll('label').forEach(l => l.style.borderColor = 'transparent'); this.parentElement.style.borderColor = 'var(--primary)';">
                                    <img src="{{ $option }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </label>
                            @endforeach
                        </div>
                        <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary);">Choose a banner image for your marketplace shop page.</p>
                    </div>
                </div>





                <h3 style="color: var(--headings); margin: 2rem 0 1.5rem 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Shop Location</h3>
                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="location-grid">
                        <div>
                            <label style="display: block; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Country</label>
                            <select name="country" class="flowexa-input-field" style="width: 100%; padding: 0.6rem 0.75rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); margin-bottom: 1.5rem;">
                                <option value="Ghana" {{ old('country', $tenant->country) === 'Ghana' ? 'selected' : '' }}>Ghana</option>
                                <option value="Nigeria" {{ old('country', $tenant->country) === 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                                <option value="Kenya" {{ old('country', $tenant->country) === 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                <option value="South Africa" {{ old('country', $tenant->country) === 'South Africa' ? 'selected' : '' }}>South Africa</option>
                            </select>

                            <x-ui.input name="city" id="settings-city" label="City" placeholder="e.g. Accra" value="{{ old('city', $tenant->city) }}" required />
                        </div>
                        <div>
                            <x-ui.input name="address" id="settings-address" label="Full Address" placeholder="e.g. 123 High Street" value="{{ old('address', $tenant->address) }}" required />
                            <x-ui.input name="landmark" id="settings-landmark" label="Landmark (Optional)" placeholder="e.g. Opposite the Mall" value="{{ old('landmark', $tenant->landmark) }}" />
                        </div>
                    </div>

                    <div style="margin-top: 1rem;">
                        <label style="display: block; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Pin Shop Location</label>
                        <div id="settings-map" style="height: 350px; border-radius: 16px; border: 1px solid var(--border); margin-bottom: 1rem;"></div>
                        <input type="hidden" name="latitude" id="settings-lat" value="{{ $tenant->latitude }}">
                        <input type="hidden" name="longitude" id="settings-lng" value="{{ $tenant->longitude }}">
                        <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary);">Reposition the marker if needed to specify the exact location for pickup.</p>
                    </div>
                </div>

                <h3 style="color: var(--headings); margin: 2rem 0 1.5rem 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Service Modules</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: -0.5rem; margin-bottom: 1.5rem;">Enable or disable the applications active in your workspace. Select the specific sub-module configuration.</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    @php
                        $modules = [
                            'ecommerce' => [
                                'name' => 'E-Commerce',
                                'icon' => 'fa-cart-shopping',
                                'desc' => 'Manage your online store, products, and online orders.',
                                'sub_modules' => ['B2C Retail', 'B2B Wholesale', 'Digital Goods']
                            ],
                            'pos' => [
                                'name' => 'Point of Sale (POS)',
                                'icon' => 'fa-cash-register',
                                'desc' => 'Process in-store sales and manage physical registers.',
                                'sub_modules' => ['Retail POS', 'Restaurant POS', 'Service POS']
                            ],
                            'inventory' => [
                                'name' => 'Inventory Management',
                                'icon' => 'fa-boxes-stacked',
                                'desc' => 'Track stock levels, locations, and movements.',
                                'sub_modules' => ['Standard Inventory', 'Multi-Warehouse', 'Manufacturing']
                            ],
                            'crm' => [
                                'name' => 'CRM & Loyalty',
                                'icon' => 'fa-users',
                                'desc' => 'Manage customers, segments, and loyalty points.',
                                'sub_modules' => ['Basic CRM', 'Advanced CRM & Loyalty']
                            ],
                            'marketing' => [
                                'name' => 'Marketing & Ads',
                                'icon' => 'fa-bullhorn',
                                'desc' => 'Run campaigns, automation rules, and ads.',
                                'sub_modules' => ['Email Marketing', 'Social Ads Integration']
                            ],
                            'supply_chain' => [
                                'name' => 'Supply Chain',
                                'icon' => 'fa-truck-fast',
                                'desc' => 'Manage suppliers and automated purchase orders.',
                                'sub_modules' => ['Basic Procurement', 'Advanced Supply Chain']
                            ],
                            'reporting' => [
                                'name' => 'Reporting & Analytics',
                                'icon' => 'fa-chart-pie',
                                'desc' => 'Generate financial and operational reports.',
                                'sub_modules' => ['Standard Reports', 'Custom BI Dashboards']
                            ],
                        ];
                    @endphp

                    @foreach($modules as $key => $module)
                    @php
                        $serviceRecord = $tenantServices->get($key);
                        $isActive = $serviceRecord && $serviceRecord->is_active;
                        $subCategory = $serviceRecord ? $serviceRecord->sub_category : $module['sub_modules'][0];
                    @endphp
                    <div style="display: flex; flex-direction: column; gap: 1rem; padding: 1.5rem; border: 1px solid var(--border); border-radius: 16px; background: var(--surface); box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class="fa-solid {{ $module['icon'] }}"></i>
                                </div>
                                <div>
                                    <strong style="color: var(--headings); display: block; font-size: 1.1rem;">{{ $module['name'] }}</strong>
                                </div>
                            </div>

                            <input type="hidden" name="services[{{ $key }}][is_active]" value="0">
                            <x-ui.switch name="services[{{ $key }}][is_active]" value="1" :checked="$isActive" id="switch-{{ $key }}" />
                        </div>

                        <p style="margin: 0; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">{{ $module['desc'] }}</p>

                        <div style="margin-top: auto; padding-top: 1.25rem; border-top: 1px solid var(--border);">
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Sub Module</label>
                            <select name="services[{{ $key }}][sub_category]" style="width: 100%; padding: 0.6rem 0.75rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none; font-size: 0.9rem; font-weight: 500; font-family: inherit;">
                                @foreach($module['sub_modules'] as $sub)
                                    <option value="{{ $sub }}" {{ $subCategory === $sub ? 'selected' : '' }}>{{ $sub }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endforeach

                </div>
                <div class="show-supply-chain-config" style="display: {{ $tenant->supply_chain_mode !== 'none' ? 'block' : 'none' }};">
                    <h3 style="color: var(--headings); margin: 2rem 0 1.5rem 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Supply Chain Configuration</h3>
                    <div style="background: var(--surface-secondary); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 2rem;">
                        <label style="display: block; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Supply Chain Role</label>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1rem;">Select how your workspace interacts with other businesses in the flowexa network.</p>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); cursor: pointer;">
                                <input type="radio" name="supply_chain_mode" value="buyer" {{ $tenant->supply_chain_mode === 'buyer' ? 'checked' : '' }}>
                                <div>
                                    <strong style="display: block; font-size: 0.95rem;">Supplying from others</strong>
                                    <span style="font-size: 0.8rem; color: var(--text-secondary);">Connect with suppliers and manage procurement.</span>
                                </div>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); cursor: pointer;">
                                <input type="radio" name="supply_chain_mode" value="supplier" {{ $tenant->supply_chain_mode === 'supplier' ? 'checked' : '' }}>
                                <div>
                                    <strong style="display: block; font-size: 0.95rem;">Being a supplier</strong>
                                    <span style="font-size: 0.8rem; color: var(--text-secondary);">Supply products to other shops in the network.</span>
                                </div>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); cursor: pointer;">
                                <input type="radio" name="supply_chain_mode" value="both" {{ $tenant->supply_chain_mode === 'both' ? 'checked' : '' }}>
                                <div>
                                    <strong style="display: block; font-size: 0.95rem;">Both</strong>
                                    <span style="font-size: 0.8rem; color: var(--text-secondary);">Operate as both a buyer and a supplier.</span>
                                </div>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); cursor: pointer;">
                                <input type="radio" name="supply_chain_mode" value="none" {{ in_array($tenant->supply_chain_mode, ['none', null]) ? 'checked' : '' }}>
                                <div>
                                    <strong style="display: block; font-size: 0.95rem;">None</strong>
                                    <span style="font-size: 0.8rem; color: var(--text-secondary);">No network supply chain features active.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <button type="submit" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 600;">Save Workspace & Modules</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const supplyChainSwitch = document.getElementById('switch-supply_chain');
            const supplyChainConfig = document.querySelector('.show-supply-chain-config');

            if (supplyChainSwitch && supplyChainConfig) {
                const toggleConfig = () => {
                    supplyChainConfig.style.display = supplyChainSwitch.checked ? 'block' : 'none';
                };

                supplyChainSwitch.addEventListener('change', toggleConfig);


                toggleConfig();
            }
        });
    </script>
</x-layouts.app>
