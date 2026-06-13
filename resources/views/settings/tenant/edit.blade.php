<x-layouts.app title="Workspace Settings">
    <x-slot:head>
        <meta name="description" content="Update your company workspace settings.">
    </x-slot:head>

    <div class="synkra-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Workspace Settings</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manage your company details and global workspace configuration.</p>
            </div>
            <a href="{{ url('invites') }}" class="synkra-btn" style="background: rgba(59, 130, 246, 0.1); color: var(--primary); padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(59, 130, 246, 0.2); transition: all 0.2s;" onmouseover="this.style.background='var(--primary)'; this.style.color='white';" onmouseout="this.style.background='rgba(59, 130, 246, 0.1)'; this.style.color='var(--primary)';">
                <i class="fa-solid fa-users"></i> Manage Users
            </a>
        </div>

        @if(session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 2rem;" />
        @endif
        
        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="Please fix the errors below." style="margin-bottom: 2rem;" />
        @endif

        <div class="synkra-card" style="padding: 2rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border);">
            <form action="{{ route('settings.workspace.update') }}" method="POST">
                @csrf
                
                <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Company Information</h3>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <x-ui.input name="name" label="Workspace Name" placeholder="e.g. Acme Corp" value="{{ old('name', $tenant->name) }}" required />
                    
                    <div>
                        <x-ui.input name="subdomain" label="Custom Subdomain (Optional)" placeholder="acme" value="{{ old('subdomain', $tenant->subdomain) }}" />
                        <p style="margin: 0.5rem 0 0 0; font-size: 0.8rem; color: var(--text-secondary);">If set, you can access your workspace at <strong style="color: var(--primary);">subdomain.synkra.test</strong></p>
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
                            <x-ui.switch name="services[{{ $key }}][is_active]" value="1" :checked="$isActive" />
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

                <div style="display: flex; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <button type="submit" class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 600;">Save Workspace & Modules</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
