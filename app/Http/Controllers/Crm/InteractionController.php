<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerInteraction;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $interactions = CustomerInteraction::with('customer')
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(20);
        return view('crm.interactions.index', compact('interactions'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $customers = Customer::where('tenant_id', $tenantId)->get();
        return view('crm.interactions.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'interaction_type' => 'required|string',
            'notes' => 'required|string',
            'interaction_date' => 'required|date',
        ]);

        CustomerInteraction::create([
            'tenant_id' => Auth::user()->tenant_id,
            'customer_id' => $request->customer_id,
            'interaction_type' => $request->interaction_type,
            'notes' => $request->notes,
            'interaction_date' => $request->interaction_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('crm.interactions.index')->with('success', 'Interaction logged.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $interaction = CustomerInteraction::with('customer')->where('tenant_id', $tenantId)->findOrFail($id);
        return view('crm.interactions.show', compact('interaction'));
    }

    public function history()
    {
        $tenantId = Auth::user()->tenant_id;
        $history = CustomerInteraction::with('customer')
            ->where('tenant_id', $tenantId)
            ->whereIn('interaction_type', ['email', 'sms', 'message'])
            ->latest()
            ->paginate(30);

        return view('crm.interactions.history', compact('history'));
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $interaction = CustomerInteraction::where('tenant_id', $tenantId)->findOrFail($id);
        $interaction->delete();

        return redirect()->route('crm.interactions.index')->with('success', 'Interaction deleted.');
    }
}
