<?php

namespace App\Http\Controllers\Api\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdCampaign;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = AdCampaign::where('tenant_id', Auth::user()->tenant_id)->latest()->get();
        return response()->json($campaigns);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'platform_id' => 'required|exists:marketing_platforms,id',
            'objective' => 'required|string',
            'daily_budget' => 'required|numeric',
        ]);

        $campaign = AdCampaign::create(array_merge($validated, [
            'tenant_id' => Auth::user()->tenant_id,
            'created_by' => Auth::id(),
            'status' => 'active',
        ]));

        return response()->json($campaign, 201);
    }

    public function show($id)
    {
        $campaign = AdCampaign::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return response()->json($campaign);
    }
}
