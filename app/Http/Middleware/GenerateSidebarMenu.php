<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Enums\UserRole;
use App\Models\EcommerceStore;
use App\Services\SidebarIndicatorService;
use App\Services\ModuleFeatureService;

class GenerateSidebarMenu
{
    public function __construct(
        private readonly SidebarIndicatorService $indicators,
        private readonly ModuleFeatureService $features
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $modules = config('permissions.modules', []);
            $userPermissions = is_string($user->permissions) ? json_decode($user->permissions, true) : ($user->permissions ?? []);

            $role = $user->role instanceof UserRole ? $user->role->value : $user->role;
            $isOwnerOrAdmin = in_array($role, ['owner', 'admin']);
            $tenantId = $user->tenant_id;
            $tenant = $user->tenant;

            $enabledFeatures = $this->features->getEnabledFeatures($tenant);

            $indicatorConfig = config('sidebar.indicators', []);
            $resolvedIndicators = $this->resolveAssignedIndicators($tenantId, $indicatorConfig, $userPermissions, $isOwnerOrAdmin);

            View::share('reorderAlertSummary', $resolvedIndicators['reorder_alerts'] ?? [
                'count' => 0,
                'variant' => null,
                'critical' => 0,
                'low' => 0,
                'active_alerts' => 0,
            ]);

            $sidebarMenu = [];

            $activeServicesData = $user->tenant
                ? $user->tenant->services()->where('is_active', true)->get(['service_name', 'sub_category'])->keyBy('service_name')
                : collect();

            $activeServices = $activeServicesData->keys()->toArray();

            $moduleMetadata = [
                'inventory' => ['label' => 'Inventory', 'icon' => 'fa-solid fa-boxes-stacked', 'url' => route('product_service.stocks.index')],
                'pos' => ['label' => 'Point of Sale', 'icon' => 'fa-solid fa-cash-register', 'url' => route('product_service.pos.index')],
                'marketing' => ['label' => 'Marketing', 'icon' => 'fa-solid fa-bullhorn', 'url' => route('marketing.campaigns.index')],
                'crm' => ['label' => 'CRM', 'icon' => 'fa-solid fa-users', 'url' => route('crm.customers.index')],
                'supply_chain' => ['label' => 'Supply Chain', 'icon' => 'fa-solid fa-truck-fast', 'url' => route('supply_chain.suppliers.index')],
                // 'payments' => ['label' => 'Payments', 'icon' => 'fa-solid fa-credit-card', 'url' => '#'],
                'ecommerce' => ['label' => 'E-Commerce', 'icon' => 'fa-solid fa-store', 'url' => route('ecommerce.stores.index')],
                'reports' => ['label' => 'Reports', 'icon' => 'fa-solid fa-chart-line', 'url' => route('reporting.index')],
            ];

            $sidebarMenu[] = ['label' => 'Dashboard', 'icon' => 'fa-solid fa-house', 'url' => url('dashboard')];

            $serviceMap = [
                'inventory' => 'inventory',
                'pos' => 'pos',
                'marketing' => 'marketing',
                'crm' => 'crm',
                'supply_chain' => 'supply_chain',
                'ecommerce' => 'ecommerce',
                'reports' => 'reporting',
            ];

            $activeStore = EcommerceStore::where('tenant_id', $user->tenant_id)->first();

            foreach ($modules as $moduleName => $actions) {
                if (isset($serviceMap[$moduleName]) && !in_array($serviceMap[$moduleName], $activeServices)) {
                    continue;
                }

                $hasAccess = false;

                if ($isOwnerOrAdmin) {
                    $hasAccess = true;
                } else {
                    if (isset($userPermissions[$moduleName]) && is_array($userPermissions[$moduleName])) {
                        foreach ($userPermissions[$moduleName] as $action => $granted) {
                            if ($granted) {
                                $hasAccess = true;
                                break;
                            }
                        }
                    }
                }

                if ($hasAccess && isset($moduleMetadata[$moduleName])) {
                    $item = $moduleMetadata[$moduleName];
                    $subitems = [];
                    $addedLabels = [];
                    $parentIndicator = null;

                    foreach ($actions as $action => $default) {
                        if (! $this->userCan($isOwnerOrAdmin, $userPermissions, $moduleName, $action)) {
                            continue;
                        }

                        if (preg_match('/^(create|edit|delete|approve|cancel|receive|report|dispose|process|verify|export|resolve|void|refund|close|track|sync|handle|initiate|reconcile|adjust|log|send|abandoned|publish|schedule|import)_/', $action)) {
                            continue;
                        }

                        $route = '#';
                        $label = ucwords(str_replace(['view_', 'manage_', 'open_'], '', $action));
                        $label = ucwords(str_replace('_', ' ', $label));

                        if ($moduleName === 'inventory') {
                            $route = match ($action) {
                                'view_products' => route('product_service.products.index'),
                                'view_categories' => route('product_service.categories.index'),
                                'view_stock' => route('product_service.stocks.index'),
                                'view_stock_movements' => route('product_service.stocks.movements'),
                                'view_adjustments' => route('product_service.stocks.adjustments.index'),
                                'transfer_stock' => route('product_service.stocks.transfers.index'),
                                'view_damages' => route('product_service.stocks.damages.index'),
                                'view_returns' => route('product_service.stocks.returns.index'),
                                'view_counts' => route('product_service.stocks.counts.index'),
                                'manage_locations' => route('product_service.stocks.locations.index'),
                                'manage_bins' => route('product_service.stocks.bins.index'),
                                'view_reorder_alerts' => route('product_service.stocks.reorder_alerts.index'),
                                default => null,
                            };

                            if ($route === null) continue;

                            if ($action === 'transfer_stock') {
                                $label = 'Transfers';
                            } elseif ($action === 'view_reorder_alerts') {
                                $label = 'Reorder Alerts';
                            }

                            // Sub-module specific items for Inventory
                            if ($action === 'view_products') {
                                if (in_array('inventory.bom', $enabledFeatures)) {
                                    $subitems[] = ['label' => 'Bill of Materials', 'url' => route('product_service.stocks.bom.index'), 'permission' => 'view_products'];
                                }
                                if (in_array('inventory.production_orders', $enabledFeatures)) {
                                    $subitems[] = ['label' => 'Production Orders', 'url' => route('product_service.stocks.production.index'), 'permission' => 'view_products'];
                                }
                            }
                        } elseif ($moduleName === 'pos') {
                            $route = match ($action) {
                                'view_orders' => route('product_service.pos.orders'),
                                'view_sessions' => route('product_service.pos.sessions'),
                                'open_session' => route('product_service.pos.index'),
                                'view_daily_sales' => route('product_service.pos.daily-sales'),
                                'manage_devices' => route('product_service.pos.devices'),
                                'view_drawer_access' => route('product_service.pos.drawer-access'),
                                'cash_drawer_access' => route('product_service.pos.drawer-access'),
                                default => null,
                            };

                            if ($route === null) continue;

                            if ($action === 'view_orders') {
                                $label = 'Orders';
                            } elseif ($action === 'view_sessions') {
                                $label = 'Sessions';
                            } elseif ($action === 'open_session') {
                                $label = 'Launch POS';
                            } elseif ($action === 'view_daily_sales') {
                                $label = 'Daily Sales';
                            } elseif ($action === 'manage_devices') {
                                $label = 'Devices';
                            } elseif ($action === 'view_drawer_access' || $action === 'cash_drawer_access') {
                                $label = 'Drawer Access';
                            }

                            // Sub-module specific items for POS
                            if ($action === 'view_orders') { // Inject after orders
                                if (in_array('pos.tables', $enabledFeatures)) {
                                    $subitems[] = ['label' => 'Tables', 'url' => route('product_service.pos.tables'), 'permission' => 'view_pos'];
                                }
                                if (in_array('pos.kitchen_display', $enabledFeatures)) {
                                    $subitems[] = ['label' => 'Kitchen Display', 'url' => route('product_service.pos.kitchen'), 'permission' => 'view_pos'];
                                }
                            }
                        } elseif ($moduleName === 'supply_chain') {
                            $tenant = $user->tenant;
                            $mode = $tenant?->supply_chain_mode ?? 'none';

                            if ($action === 'view_suppliers' && !in_array($mode, ['buyer', 'both'])) {
                                continue;
                            }
                            if (in_array($action, ['view_product_supplier_list', 'manage_import_stocks']) && !in_array($mode, ['supplier', 'both'])) {
                                continue;
                            }
                            if ($action === 'view_approvals' && $mode === 'none') {
                                continue;
                            }

                            $route = match ($action) {
                                'view_suppliers' => route('supply_chain.suppliers.index'),
                                'view_purchase_orders' => route('supply_chain.purchasing.index'),
                                'view_receiving_reports' => route('supply_chain.receiving.index'),
                                'view_contracts' => route('supply_chain.contracts.index'),
                                'view_forecasts' => route('supply_chain.forecast.index'),
                                'view_shipments' => route('supply_chain.shipments.index'),
                                'view_scm_reports' => route('supply_chain.reports.index'),
                                'view_product_supplier_list' => route('supply_chain.supplier_list.index'),
                                'manage_import_stocks' => route('supply_chain.import_stocks.index'),
                                'view_approvals' => route('supply_chain.approvals.index'),
                                default => null,
                            };

                            if ($route === null) continue;

                            if ($action === 'view_forecasts' && !in_array('supply_chain.forecasting', $enabledFeatures)) {
                                continue;
                            }
                            if ($action === 'view_shipments' && !in_array('supply_chain.shipment_tracking', $enabledFeatures)) {
                                continue;
                            }
                        } elseif ($moduleName === 'marketing') {
                            $route = match ($action) {
                                'view_campaigns' => route('marketing.campaigns.index'),
                                'manage_platform_connections' => route('marketing.connections.index'),
                                'manage_budgets' => route('marketing.subscriptions.index'),
                                'view_performance' => route('marketing.campaigns.index'), // placeholder
                                'manage_product_feeds' => null, // removed if no page
                                'view_automation_rules' => null, // removed if no page
                                default => '#',
                            };

                            if ($route === null) continue;

                            if ($action === 'manage_budgets') {
                                $label = 'Plan & Subscriptions';
                            } elseif ($action === 'manage_platform_connections') {
                                $label = 'Connect Accounts';
                            } elseif ($action === 'view_campaigns') {
                                $label = 'Ad Campaigns';
                            } elseif ($action === 'view_performance') {
                                $label = 'Analytics';
                            }
                        } elseif ($moduleName === 'crm') {
                            $route = match ($action) {
                                'view_customers' => route('crm.customers.index'),
                                'view_segments' => route('crm.segments.index'),
                                'view_interactions' => route('crm.interactions.index'),
                                'view_communication_history' => route('crm.communication_history'),
                                'manage_automation_triggers' => route('crm.automations.index'),
                                'view_customer_analytics' => route('crm.analytics'),
                                'view_loyalty_programs' => route('crm.loyalty.index'),
                                default => null,
                            };

                            if ($route === null) continue;

                            if ($action === 'view_loyalty_programs' && !in_array('crm.loyalty_points', $enabledFeatures)) {
                                continue;
                            }
                        } elseif ($moduleName === 'ecommerce') {
                            $route = match ($action) {
                                'view_store' => route('ecommerce.stores.index'),
                                'view_orders' => $activeStore ? route('ecommerce.orders.index', $activeStore->id) : route('ecommerce.stores.index'),
                                'manage_product_reviews' => $activeStore ? route('ecommerce.reviews.index', $activeStore->id) : route('ecommerce.stores.index'),
                                'view_ecommerce_analytics' => $activeStore ? route('ecommerce.analytics', $activeStore->id) : route('ecommerce.stores.index'),
                                default => null,
                            };

                            if ($route === null) {
                                continue;
                            }

                            if ($action === 'view_store') {
                                $label = 'Store';
                            } elseif ($action === 'view_orders') {
                                $label = 'Orders';
                            } elseif ($action === 'manage_product_reviews') {
                                $label = 'Product Review';
                            } elseif ($action === 'view_ecommerce_analytics') {
                                $label = 'Analytics';
                            }
                        } elseif ($moduleName === 'reports') {
                            $route = match ($action) {
                                'view_dashboards' => route('reporting.dashboards'),
                                'view_reports' => route('reporting.reports'),
                                'view_kpi_metrics' => route('reporting.kpi'),
                                'view_audit_logs' => route('reporting.audit_logs'),
                                default => null,
                            };

                            if ($route === null) continue;

                        } elseif ($moduleName === 'settings') {
                            if ($action === 'view_company_settings') {
                                $route = route('settings.workspace.edit');
                                $label = 'Workspace';
                            } elseif ($action === 'view_billing') {
                                $route = route('settings.subaccounts.index');
                                $label = 'Billing';
                            } elseif ($action === 'manage_tenants') {
                                $route = route('settings.services.index');
                                $label = 'Tenants';
                            }
                        }

                        if (in_array($label, $addedLabels)) {
                            continue;
                        }

                        $subitem = ['label' => $label, 'url' => $route, 'permission' => $action];

                        if (isset($indicatorConfig[$action], $resolvedIndicators[$indicatorConfig[$action]['resolver']])) {
                            $indicator = $resolvedIndicators[$indicatorConfig[$action]['resolver']];
                            $subitem['indicator'] = $indicator;

                            if (($indicatorConfig[$action]['rollup_parent'] ?? false) && $this->shouldReplaceParentIndicator($parentIndicator, $indicator)) {
                                $parentIndicator = $indicator;
                            }
                        }

                        $subitems[] = $subitem;
                        $addedLabels[] = $label;
                    }

                    if (count($subitems) > 0) {
                        $item['subitems'] = $subitems;
                    }

                    if ($parentIndicator) {
                        $item['indicator'] = $parentIndicator;
                    }

                    $sidebarMenu[] = $item;
                }
            }

            View::share('sidebarMenu', $sidebarMenu);
            View::share('enabledFeatures', $enabledFeatures);
        }

        return $next($request);
    }

    private function userCan(bool $isOwnerOrAdmin, array $userPermissions, string $moduleName, string $action): bool
    {
        return $isOwnerOrAdmin
            || (isset($userPermissions[$moduleName][$action]) && $userPermissions[$moduleName][$action]);
    }

    private function resolveAssignedIndicators(
        ?string $tenantId,
        array $indicatorConfig,
        array $userPermissions,
        bool $isOwnerOrAdmin
    ): array {
        if (! $tenantId) {
            return [];
        }

        $resolved = [];

        foreach ($indicatorConfig as $permission => $config) {
            $module = $this->permissionModule($permission);
            if (! $this->userCan($isOwnerOrAdmin, $userPermissions, $module, $permission)) {
                continue;
            }

            $indicator = $this->indicators->resolve($config['resolver'], $tenantId);
            if ($indicator) {
                $resolved[$config['resolver']] = $indicator;
            }
        }

        return $resolved;
    }

    private function permissionModule(string $permission): string
    {
        foreach (config('permissions.modules', []) as $moduleName => $actions) {
            if (array_key_exists($permission, $actions)) {
                return $moduleName;
            }
        }

        return 'inventory';
    }

    private function shouldReplaceParentIndicator(?array $current, array $candidate): bool
    {
        if (! $current) {
            return true;
        }

        $priority = ['danger' => 3, 'warning' => 2, 'info' => 1];

        return ($priority[$candidate['variant']] ?? 0) > ($priority[$current['variant']] ?? 0);
    }
}
