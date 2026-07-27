<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\EcommerceStore;
use App\Models\EcommerceOrder;
use App\Models\EcommerceOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EcommerceAnalyticsController extends Controller
{
    public function index(EcommerceStore $store, Request $request)
    {
        $this->authorizeStore($store);

        $period = $request->get('period', '30d');
        $startDate = match($period) {
            '7d' => Carbon::now()->subDays(7),
            '30d' => Carbon::now()->subDays(30),
            '90d' => Carbon::now()->subDays(90),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->subDays(30),
        };

        // Key Metrics
        $totalRevenue = $store->orders()->where('payment_status', 'paid')->where('created_at', '>=', $startDate)->sum('total_amount');
        $totalOrders = $store->orders()->where('created_at', '>=', $startDate)->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Sales over time
        $salesData = $store->orders()
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Products
        $topProducts = EcommerceOrderItem::whereHas('order', function($q) use ($store, $startDate) {
                $q->where('store_id', $store->id)->where('payment_status', 'paid')->where('created_at', '>=', $startDate);
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('ecommerce.analytics.index', compact('store', 'totalRevenue', 'totalOrders', 'averageOrderValue', 'salesData', 'topProducts', 'period'));
    }

    private function authorizeStore(EcommerceStore $store)
    {
        if ($store->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
    }
}
