<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Enums\UserRole;

class GenerateSidebarMenu
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $modules = config('permissions.modules', []);
            $userPermissions = is_string($user->permissions) ? json_decode($user->permissions, true) : ($user->permissions ?? []);
            
            $role = $user->role instanceof UserRole ? $user->role->value : $user->role;
            $isOwnerOrAdmin = in_array($role, ['owner', 'admin']);
            
            $sidebarMenu = [];
            
            // Define icons and labels for each module
            $moduleMetadata = [
                'inventory' => ['label' => 'Inventory', 'icon' => 'fa-solid fa-boxes-stacked', 'url' => route('product_service.stocks.index')],
                'pos' => ['label' => 'Point of Sale', 'icon' => 'fa-solid fa-cash-register', 'url' => route('product_service.pos.index')],
                'marketing' => ['label' => 'Marketing', 'icon' => 'fa-solid fa-bullhorn', 'url' => '#'],
                'crm' => ['label' => 'CRM', 'icon' => 'fa-solid fa-users', 'url' => '#'],
                'supply_chain' => ['label' => 'Supply Chain', 'icon' => 'fa-solid fa-truck-fast', 'url' => route('supply_chain.suppliers.index')],
                'payments' => ['label' => 'Payments', 'icon' => 'fa-solid fa-credit-card', 'url' => '#'],
                'ecommerce' => ['label' => 'E-Commerce', 'icon' => 'fa-solid fa-store', 'url' => '#'],
                'reports' => ['label' => 'Reports', 'icon' => 'fa-solid fa-chart-line', 'url' => '#'],
                'settings' => ['label' => 'Settings', 'icon' => 'fa-solid fa-gear', 'url' => route('settings.workspace.edit')],
            ];

            // Always add dashboard
            $sidebarMenu[] = ['label' => 'Dashboard', 'icon' => 'fa-solid fa-house', 'url' => url('dashboard')];

            foreach ($modules as $moduleName => $actions) {
                $hasAccess = false;
                
                if ($isOwnerOrAdmin) {
                    $hasAccess = true;
                } else {
                    // Check if user has at least one permission in this module
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
                    
                    // Add specific accessible actions as submenus, mapping to real routes
                    foreach ($actions as $action => $default) {
                        if ($isOwnerOrAdmin || (isset($userPermissions[$moduleName][$action]) && $userPermissions[$moduleName][$action])) {
                            $route = '#';
                            $label = ucwords(str_replace('_', ' ', $action));

                            // Map actions to defined application routes
                            if ($moduleName === 'inventory') {
                                if ($action === 'view_products') $route = route('product_service.products.index');
                                elseif ($action === 'view_categories') $route = route('product_service.categories.index');
                                elseif ($action === 'view_stock') $route = route('product_service.stocks.index');
                                elseif ($action === 'view_stock_movements') $route = route('product_service.stocks.movements');
                                elseif ($action === 'manage_locations') $route = route('product_service.stocks.locations.index');
                                elseif ($action === 'manage_bins') $route = route('product_service.stocks.bins.index');
                            } elseif ($moduleName === 'pos') {
                                if ($action === 'view_orders') {
                                    $route = route('product_service.pos.orders');
                                    $label = 'View Orders';
                                }
                                elseif ($action === 'view_sessions') {
                                    $route = route('product_service.pos.sessions');
                                    $label = 'View Sessions';
                                }
                                elseif ($action === 'open_session') {
                                    $route = route('product_service.pos.index');
                                    $label = 'Launch POS Terminal';
                                }
                            } elseif ($moduleName === 'supply_chain') {
                                if ($action === 'view_suppliers') $route = route('supply_chain.suppliers.index');
                                elseif ($action === 'view_purchase_orders') $route = route('supply_chain.purchasing.index');
                                elseif ($action === 'view_receiving_reports') $route = route('supply_chain.receiving.index');
                                elseif ($action === 'view_forecasts') $route = route('supply_chain.forecast.index');
                            } elseif ($moduleName === 'settings') {
                                if ($action === 'view_company_settings') $route = route('settings.workspace.edit');
                                elseif ($action === 'view_billing') $route = route('settings.subaccounts.index');
                                elseif ($action === 'manage_tenants') $route = route('settings.services.index');
                            }

                            // Include all subitems so the developer can visually see the layout.
                            // If a route hasn't been built yet, it will fallback to '#'
                            $subitems[] = ['label' => $label, 'url' => $route];
                        }
                    }
                    
                    if (count($subitems) > 0) {
                        $item['subitems'] = $subitems;
                    }

                    $sidebarMenu[] = $item;
                }
            }
            
            // Share the globally compiled menu with all Blade views
            View::share('sidebarMenu', $sidebarMenu);
        }

        return $next($request);
    }
}
