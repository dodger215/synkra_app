@props(['module', 'permissions'])

@php
    $moduleNames = [
        'pos' => 'POS - Point of Sale System',
        'crm' => 'CRM - Customer Relationship Management',
        'supply_chain' => 'SCM - Supply Chain Management',
        'ecommerce' => 'E-Commerce & Storefront',
        'inventory' => 'Inventory Management',
        'marketing' => 'Marketing & Advertising',
        'payments' => 'Payments & Finance',
        'reports' => 'Reporting & Analytics',
        'settings' => 'System Settings'
    ];
    $displayName = $moduleNames[$module] ?? ucwords(str_replace('_', ' ', $module));
@endphp

<div class="synkra-module-select">
    <div class="synkra-module-header" onclick="toggleModuleDropdown('{{ $module }}')" style="padding: 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; z-index: 2; position: relative;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div onclick="event.stopPropagation();" style="display: flex; align-items: center; padding-top: 5px;">
                <x-ui.checkbox 
                    id="select_all_{{ $module }}" 
                    onChange="toggleAllPermissions('{{ $module }}', this.checked)" 
                />
            </div>
            <h4 style="margin: 0; color: var(--headings); font-weight: 600;">
                <i class="fa-solid fa-puzzle-piece" style="margin-right: 8px; color: var(--primary);"></i>
                {{ $displayName }}
            </h4>
        </div>
        <i class="fa-solid fa-chevron-down" id="icon_{{ $module }}" style="transition: transform 0.3s ease; color: var(--text-secondary);"></i>
    </div>
    <div class="synkra-module-body" id="body_{{ $module }}" style="display: none; padding: 1.5rem; border: 1px solid var(--border); border-top: none; border-radius: 0 0 8px 8px; background: var(--surface-secondary); margin-top: -5px; position: relative; z-index: 1; max-height: 250px; overflow-y: auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            @foreach($permissions as $perm => $default)
                <div>
                    <x-ui.checkbox 
                        name="services[{{ $module }}][{{ $perm }}]" 
                        id="perm_{{ $module }}_{{ $perm }}" 
                        class="perm-checkbox-{{ $module }}" 
                        value="1"
                        label="{{ ucwords(str_replace('_', ' ', $perm)) }}" 
                    />
                </div>
            @endforeach
        </div>
    </div>
</div>
