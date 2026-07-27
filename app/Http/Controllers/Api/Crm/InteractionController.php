<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerInteraction;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function index()
    {
        $interactions = CustomerInteraction::where('tenant_id', Auth::user()->tenant_id)->latest()->paginate(20);
        return response()->json($interactions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'interaction_type' => 'required|string',
            'notes' => 'required|string',
            'interaction_date' => 'required|date',
        ]);

        $interaction = CustomerInteraction::create(array_merge($validated, [
            'tenant_id' => Auth::user()->tenant_id,
            'created_by' => Auth::id(),
        ]));

        return response()->json($interaction, 201);
    }
}
