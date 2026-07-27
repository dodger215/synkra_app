<?php

namespace App\Http\Controllers\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DemandForecast;
use App\Models\ReorderAlert;
use Illuminate\Support\Facades\Auth;

class ForecastController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $forecasts = DemandForecast::with('product')->where('tenant_id', $tenantId)->get();
        $alerts = ReorderAlert::with('product')->where('tenant_id', $tenantId)->where('is_resolved', false)->get();

        return view('supply_chain.forecast.index', compact('forecasts', 'alerts'));
    }

    public function generateForecast(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;


        \App\Jobs\GenerateDemandForecasts::dispatch($tenantId);

        return redirect()->route('supply_chain.forecast.index')->with('success', 'Forecast generation job has been queued and will run in the background. You will be notified when it completes.');
    }

    public function resolveAlert($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $alert = ReorderAlert::where('tenant_id', $tenantId)->findOrFail($id);
        $alert->update(['is_resolved' => true]);

        return redirect()->route('supply_chain.forecast.index')->with('success', 'Alert resolved.');
    }
}
