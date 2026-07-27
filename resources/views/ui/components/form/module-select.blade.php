@props(['module', 'permissions'])

@php
    $moduleInfo = [
        'pos' => [
            'name' => 'POS - Point of Sale',
            'desc' => 'Process in-person transactions and manage physical registers.',
            'scenario' => 'Ideal for physical stores or pop-up shops.',
            'includes' => 'Register management, receipt printing, daily sales reports.'
        ],
        'crm' => [
            'name' => 'CRM & Loyalty',
            'desc' => 'Build customer profiles and run reward programs.',
            'scenario' => 'Use to retain customers and track purchase history.',
            'includes' => 'Customer segments, points system, interaction logs.'
        ],
        'supply_chain' => [
            'name' => 'SCM - Supply Chain',
            'desc' => 'Automate procurement and manage supplier relationships.',
            'scenario' => 'Perfect for wholesalers or businesses with external vendors.',
            'includes' => 'Purchase orders, receiving reports, supplier contracts.'
        ],
        'ecommerce' => [
            'name' => 'E-Commerce Storefront',
            'desc' => 'Deploy a dedicated standalone storefront for your brand with a private URL.',
            'scenario' => 'When active, your brand has its own independent storefront. When disabled, your brand and products are featured directly in the shared marketplace discovery.',
            'includes' => 'Custom pages, theme builder, standalone checkout, custom domain support.'
        ],
        'inventory' => [
            'name' => 'Inventory Management',
            'desc' => 'Advanced tracking of stock levels across multiple locations.',
            'scenario' => 'Essential for managing warehouse stock and product movements.',
            'includes' => 'Stock adjustments, transfers, reorder alerts.'
        ],
        'marketing' => [
            'name' => 'Marketing & Ads',
            'desc' => 'Execute marketing campaigns and social media integrations.',
            'scenario' => 'Drive traffic to your store via automated ads.',
            'includes' => 'Email campaigns, platform connections, automation rules.'
        ],
        'reports' => [
            'name' => 'Reporting & Analytics',
            'desc' => 'Gain deep insights into your business performance.',
            'scenario' => 'Monitor growth, profitability, and operational efficiency.',
            'includes' => 'Custom BI dashboards, KPI tracking, financial reports.'
        ],
    ];

    $info = $moduleInfo[$module] ?? [
        'name' => ucwords(str_replace('_', ' ', $module)),
        'desc' => 'Enable this module to unlock additional features.',
        'scenario' => 'General business management.',
        'includes' => 'Standard management tools.'
    ];
@endphp

<div class="flowexa-module-select">
    <div class="flowexa-module-header" style="padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; display: flex; flex-direction: column; gap: 1rem; position: relative; transition: all 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="display: flex; align-items: center;">
                    <x-ui.checkbox
                        id="select_all_{{ $module }}"
                        onChange="toggleAllPermissions('{{ $module }}', this.checked)"
                    />
                </div>
                <h4 style="margin: 0; color: var(--headings); font-weight: 700; font-size: 1rem;">
                    <i class="fa-solid fa-puzzle-piece" style="margin-right: 8px; color: var(--primary);"></i>
                    {{ $info['name'] }}
                </h4>
            </div>

            <div style="position: relative;" class="module-help-container">
                <button type="button" onclick="toggleModuleInfo('{{ $module }}')" style="background: none; border: none; padding: 5px; cursor: pointer; color: var(--text-secondary); transition: all 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                    <i class="fa-solid fa-circle-info" style="font-size: 1.1rem;"></i>
                </button>
            </div>
        </div>

        <!-- Info Panel: Hidden by default -->
        <div id="info_{{ $module }}" style="display: none; padding: 1rem; background: var(--surface-secondary); border-radius: 8px; border: 1px solid var(--border); margin-top: 0.5rem; animation: slideDown 0.3s ease-out;">
            <p style="margin: 0 0 0.75rem 0; font-size: 0.85rem; color: var(--text-primary); line-height: 1.5; font-weight: 500;">{{ $info['desc'] }}</p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.75rem;">
                <p style="margin: 0; color: var(--text-primary);"><span style="font-weight: 800; color: var(--primary); text-transform: uppercase; font-size: 0.65rem; margin-right: 5px; letter-spacing: 0.5px;">Scenario:</span> {{ $info['scenario'] }}</p>
                <p style="margin: 0; color: var(--text-primary); opacity: 0.9;"><span style="font-weight: 800; color: var(--text-secondary); text-transform: uppercase; font-size: 0.65rem; margin-right: 5px; letter-spacing: 0.5px;">Includes:</span> {{ $info['includes'] }}</p>
            </div>
        </div>

        <div class="flowexa-module-body" id="body_{{ $module }}" style="display: none; padding-top: 1.5rem; border-top: 1px solid var(--border); margin-top: 0.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <p style="font-size: 0.7rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase; margin: 0; letter-spacing: 0.5px;">Advanced Permissions</p>
                <button type="button" onclick="toggleModuleDropdown('{{ $module }}')" style="background: none; border: none; color: var(--text-secondary); font-size: 0.7rem; cursor: pointer; text-decoration: underline;">Hide</button>
            </div>
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

        <!-- Toggle for Permissions -->
        <div style="display: flex; justify-content: center; margin-top: 0.5rem;">
            <button type="button" onclick="toggleModuleDropdown('{{ $module }}')" style="background: none; border: none; color: var(--text-secondary); font-size: 0.7rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 5px;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                <span>CONFIG PERMISSIONS</span>
                <i class="fa-solid fa-chevron-down" id="icon_{{ $module }}" style="transition: transform 0.3s;"></i>
            </button>
        </div>
    </div>
</div>

