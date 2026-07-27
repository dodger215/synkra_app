<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarketingPlatform;
use App\Models\MarketingPlatformConnection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PlatformConnectionController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $platforms = MarketingPlatform::where('is_available', true)->get();
        $connections = MarketingPlatformConnection::where('tenant_id', $tenantId)->get()->keyBy('platform_id');

        return view('marketing.connections.index', compact('platforms', 'connections'));
    }

    public function connect($platformId)
    {
        $tenantId = Auth::user()->tenant_id;
        $platform = MarketingPlatform::findOrFail($platformId);

        $subscription = \App\Models\MarketingSubscription::where('tenant_id', $tenantId)->where('status', 'active')->first();

        if (!$subscription) {
            return redirect()->route('marketing.subscriptions.index')->with('error', 'Please subscribe to a plan to connect platforms.');
        }

        $allowedPlatforms = $subscription->features['allowed_platforms'] ?? [];
        if (!in_array($platform->platform_name, $allowedPlatforms)) {
            return redirect()->route('marketing.subscriptions.index')->with('error', "Your current plan doesn't include {$platform->platform_name}. Please upgrade.");
        }

        try {
            $service = \App\Services\Marketing\MarketingServiceFactory::make($platform->platform_name);
            return redirect()->away($service->getAuthUrl());
        } catch (\Exception $e) {
            // Fallback for unsupported platforms in factory
            return redirect()->route('marketing.connections.callback', [
                'platform_id' => $platformId,
                'code' => 'simulated_auth_code'
            ]);
        }
    }

    public function callback(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $platformId = $request->platform_id;
        $platform = MarketingPlatform::findOrFail($platformId);

        // Simulate getting token and account info
        $connection = MarketingPlatformConnection::updateOrCreate(
            ['tenant_id' => $tenantId, 'platform_id' => $platformId],
            [
                'external_account_id' => 'ACT_' . strtoupper(substr(uniqid(), -8)),
                'external_account_name' => $platform->platform_name . ' Business Account',
                'access_token' => 'simulated_access_token_' . uniqid(),
                'refresh_token' => 'simulated_refresh_token_' . uniqid(),
                'expires_at' => now()->addDays(30),
                'is_active' => true,
            ]
        );

        return redirect()->route('marketing.connections.index')->with('success', "Connected to {$platform->platform_name} successfully.");
    }

    public function disconnect($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $connection = MarketingPlatformConnection::where('tenant_id', $tenantId)->findOrFail($id);
        $connection->delete();

        return redirect()->back()->with('success', 'Platform disconnected.');
    }
}
