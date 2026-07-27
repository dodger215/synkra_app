<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrmAutomationTrigger;
use Illuminate\Support\Facades\Auth;

class AutomationController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $automations = CrmAutomationTrigger::where('tenant_id', $tenantId)->get();
        return view('crm.automations.index', compact('automations'));
    }

    public function create()
    {
        return view('crm.automations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'event_type' => 'required|string',
            'action_type' => 'required|string',
        ]);

        CrmAutomationTrigger::create(array_merge($request->all(), [
            'tenant_id' => Auth::user()->tenant_id
        ]));

        return redirect()->route('crm.automations.index')->with('success', 'Automation trigger created.');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $automation = CrmAutomationTrigger::where('tenant_id', $tenantId)->findOrFail($id);
        $automation->delete();

        return redirect()->route('crm.automations.index')->with('success', 'Automation deleted.');
    }
}
