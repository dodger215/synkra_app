<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerInteraction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CrmAnalyticsController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $totalCustomers = Customer::where('tenant_id', $tenantId)->count();
        $totalSpent = Customer::where('tenant_id', $tenantId)->sum('total_spent');

        $customerTiers = Customer::where('tenant_id', $tenantId)
            ->select('tier', DB::raw('count(*) as count'))
            ->groupBy('tier')
            ->get();

        $interactionVolume = CustomerInteraction::where('tenant_id', $tenantId)
            ->select(DB::raw('date(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topCustomers = Customer::where('tenant_id', $tenantId)
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get();

        return view('crm.analytics.index', compact(
            'totalCustomers', 'totalSpent', 'customerTiers', 'interactionVolume', 'topCustomers'
        ));
    }
}
