<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoyaltyProgram;
use App\Models\LoyaltyTransaction;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoyaltyController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $programs = LoyaltyProgram::where('tenant_id', $tenantId)->get();
        $recentTransactions = LoyaltyTransaction::with('customer')
            ->where('tenant_id', $tenantId)
            ->latest()
            ->limit(10)
            ->get();

        return view('crm.loyalty.index', compact('programs', 'recentTransactions'));
    }

    public function programs()
    {
        $tenantId = Auth::user()->tenant_id;
        $programs = LoyaltyProgram::where('tenant_id', $tenantId)->get();
        return view('crm.loyalty.programs', compact('programs'));
    }

    public function storeProgram(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'points_per_currency' => 'required|numeric|min:0',
        ]);

        LoyaltyProgram::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'points_per_currency' => $request->points_per_currency,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Loyalty program created.');
    }

    public function adjustPoints(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'points' => 'required|integer',
            'reason' => 'required|string',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($request->customer_id);

        DB::transaction(function() use ($customer, $request, $tenantId) {
            $customer->increment('loyalty_points', $request->points);

            LoyaltyTransaction::create([
                'tenant_id' => $tenantId,
                'customer_id' => $customer->id,
                'points' => $request->points,
                'transaction_type' => $request->points > 0 ? 'earned' : 'redeemed',
                'description' => $request->reason,
            ]);
        });

        return redirect()->back()->with('success', 'Customer points adjusted.');
    }
}
