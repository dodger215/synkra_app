<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdCampaign;
use App\Models\MarketingPlatform;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $campaigns = AdCampaign::with('platform')->where('tenant_id', $tenantId)->latest()->get();
        return view('marketing.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $platforms = MarketingPlatform::where('is_available', true)->get();
        return view('marketing.campaigns.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'platform_id' => 'required|exists:marketing_platforms,id',
            'objective' => 'required|string',
            'daily_budget' => 'required|numeric|min:0',
        ]);

        $platform = \App\Models\MarketingPlatform::findOrFail($request->platform_id);

        $campaignData = [
            'tenant_id' => Auth::user()->tenant_id,
            'created_by' => Auth::id(),
            'status' => 'active',
            'platform_id' => $request->platform_id,
            'campaign_name' => $request->campaign_name,
            'objective' => $request->objective,
            'daily_budget' => $request->daily_budget,
        ];

        try {
            $service = \App\Services\Marketing\MarketingServiceFactory::make($platform->platform_name);
            $externalData = $service->createCampaign($request->all());
            $campaignData['external_campaign_id'] = $externalData['external_id'];
        } catch (\Exception $e) {
            $campaignData['status'] = 'draft';
        }

        AdCampaign::create($campaignData);

        return redirect()->route('marketing.campaigns.index')->with('success', 'Campaign processed successfully.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $campaign = AdCampaign::with(['platform', 'adSets'])->where('tenant_id', $tenantId)->findOrFail($id);
        return view('marketing.campaigns.show', compact('campaign'));
    }
}
