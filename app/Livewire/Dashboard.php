<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PosOrder;
use App\Models\EcommerceOrder;
use App\Models\StockBalance;
use App\Models\Customer;
use App\Models\AdCampaign;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\CustomerInteraction;
use App\Models\MarketingPlatformConnection;
use App\Models\MarketingSubscription;
use App\Models\PosOrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    public $metrics = [];
    public $recentActivities = [];
    public $lowStock = [];
    public $revenueData = [];
    public $topProducts = [];
    public $activeSessions = [];
    public $teamMembers = [];
    public $goalProgress = 0;
    public $activeServices = [];

    // CRM
    public $crmStats = [];
    // Marketing
    public $marketingStats = [];
    // Supply Chain
    public $supplyChainStats = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = Auth::user();
        if (!$user) return;

        $tenant = $user->tenant;
        if (!$tenant) return;

        // Load active services
        $this->activeServices = $tenant->services()
            ->where('is_active', true)
            ->pluck('service_name')
            ->map(fn($s) => strtolower($s))
            ->toArray();

        // Fallback for Owners/Admins if no services are recorded yet
        $roleStr = strtolower($user->role instanceof \UnitEnum ? $user->role->value : $user->role);
        if (empty($this->activeServices) && in_array($roleStr, ['owner', 'admin'])) {
            $this->activeServices = ['inventory', 'pos', 'ecommerce', 'crm', 'marketing', 'supply_chain', 'reporting'];
        }

        $hasInventory = in_array('inventory', $this->activeServices);
        $hasPos = in_array('pos', $this->activeServices);
        $hasEcommerce = in_array('ecommerce', $this->activeServices);
        $hasCrm = in_array('crm', $this->activeServices);
        $hasMarketing = in_array('marketing', $this->activeServices);
        $hasSupplyChain = in_array('supply_chain', $this->activeServices);

        // 1. Basic Metrics
        $this->teamMembers = $tenant->users()->latest()->take(5)->get()->map(fn($u) => [
            'name' => $u->name,
            'role' => strtolower($u->role instanceof \UnitEnum ? $u->role->value : $u->role),
            'email' => $u->email,
            'initial' => strtoupper(substr($u->name, 0, 1))
        ])->all();

        $totalUsers = $tenant->users()->count();
        $totalProducts = $hasInventory ? $tenant->products()->count() : 0;
        $totalPosOrders = $hasPos ? $tenant->posOrders()->count() : 0;
        $totalEcommerceOrders = $hasEcommerce ? $tenant->ecommerceOrders()->count() : 0;
        $totalOrders = $totalPosOrders + $totalEcommerceOrders;

        $todayRevenue = 0;
        if ($hasPos) {
            $todayRevenue += (float)$tenant->posOrders()->whereDate('completed_at', Carbon::today())->sum('total_amount');
        }
        if ($hasEcommerce) {
            $todayRevenue += (float)$tenant->ecommerceOrders()->whereDate('ordered_at', Carbon::today())->sum('total_amount');
        }

        $this->metrics = [
            'users_count' => $totalUsers,
            'products_count' => $totalProducts,
            'orders_count' => $totalOrders,
            'today_revenue' => $todayRevenue,
        ];

        // 2. CRM Stats
        if ($hasCrm) {
            $this->crmStats = [
                'total_customers' => $tenant->customers()->count(),
                'recent_interactions' => CustomerInteraction::where('tenant_id', $tenant->id)->where('created_at', '>=', now()->subDays(7))->count(),
                'active_segments' => $tenant->customerSegments()->count(),
            ];
        }

        // 3. Marketing Stats
        if ($hasMarketing) {
            $this->marketingStats = [
                'active_campaigns' => $tenant->adCampaigns()->where('status', 'active')->count(),
                'connected_platforms' => MarketingPlatformConnection::where('tenant_id', $tenant->id)->where('is_active', true)->count(),
                'active_subscription' => MarketingSubscription::where('tenant_id', $tenant->id)->where('status', 'active')->first()?->plan_name,
            ];
        }

        // 4. Supply Chain Stats
        if ($hasSupplyChain) {
            $this->supplyChainStats = [
                'active_suppliers' => $tenant->suppliers()->where('connection_status', 'approved')->count(),
                'pending_requests' => $tenant->receivedSupplierRequests()->where('connection_status', 'pending')->count(),
                'open_pos' => $tenant->purchaseOrders()->whereIn('status', ['approved', 'shipped', 'partially_received'])->count(),
            ];
        }

        // 5. Recent Activities
        $pos = collect();
        if ($hasPos) {
            $pos = $tenant->posOrders()->with('customer')->latest()->limit(5)->get()->map(function($o) {
                return [
                    'type' => 'POS Order',
                    'id' => substr($o->id, 0, 8),
                    'amount' => $o->total_amount,
                    'customer' => $o->customer?->first_name ?? 'Guest',
                    'time' => $o->completed_at,
                    'icon' => 'fa-cash-register'
                ];
            });
        }

        $eco = collect();
        if ($hasEcommerce) {
            $eco = $tenant->ecommerceOrders()->latest()->limit(5)->get()->map(function($o) {
                return [
                    'type' => 'Online Order',
                    'id' => substr($o->id, 0, 8),
                    'amount' => $o->total_amount,
                    'customer' => 'Customer',
                    'time' => $o->ordered_at,
                    'icon' => 'fa-globe'
                ];
            });
        }

        $this->recentActivities = $pos->concat($eco)->sortByDesc('time')->take(6)->all();

        // 6. Low Stock Alerts
        $this->lowStock = [];
        if ($hasInventory) {
            $this->lowStock = StockBalance::whereHas('product', function($q) use ($tenant) {
                $q->where('tenant_id', $tenant->id);
            })
            ->with('product')
            ->get()
            ->filter(fn($b) => $b->quantity_on_hand <= ($b->product->reorder_point ?? 0))
            ->take(5)
            ->map(fn($b) => [
                'name' => $b->product->name,
                'qty' => (float)$b->quantity_on_hand,
                'min' => (float)$b->product->reorder_point
            ])
            ->all();
        }

        // 7. Revenue Graph
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $rev = 0;
            if ($hasPos) {
                $rev += (float)$tenant->posOrders()->whereDate('completed_at', $date)->sum('total_amount');
            }
            if ($hasEcommerce) {
                $rev += (float)$tenant->ecommerceOrders()->whereDate('ordered_at', $date)->sum('total_amount');
            }

            $days[] = [
                'day' => $date->format('D'),
                'amount' => $rev
            ];
        }
        $this->revenueData = $days;

        // 8. Advanced: Top Products
        $this->topProducts = [];
        if ($hasPos) {
            $this->topProducts = PosOrderItem::whereHas('order', function($q) use ($tenant) {
                $q->where('tenant_id', $tenant->id);
            })
            ->with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(4)
            ->get()
            ->map(fn($i) => [
                'name' => $i->product->name ?? 'Deleted Product',
                'sales' => $i->total_qty
            ])->all();
        }

        // 9. Advanced: Active Sessions
        $this->activeSessions = [];
        if ($hasPos) {
            $this->activeSessions = $tenant->posSessions()
                ->whereNull('ended_at')
                ->with(['device', 'cashier'])
                ->get()
                ->map(fn($s) => [
                    'cashier' => $s->cashier->name,
                    'device' => $s->device->device_name ?? 'Manual',
                    'started' => $s->started_at->diffForHumans()
                ])->all();
        }

        // 10. Progress toward monthly goal
        $monthlyRevenue = 0;
        if ($hasPos) {
            $monthlyRevenue += (float)$tenant->posOrders()->whereMonth('completed_at', Carbon::now()->month)->sum('total_amount');
        }
        if ($hasEcommerce) {
            $monthlyRevenue += (float)$tenant->ecommerceOrders()->whereMonth('ordered_at', Carbon::now()->month)->sum('total_amount');
        }
        $this->goalProgress = min(round(($monthlyRevenue / 5000) * 100), 100);
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
