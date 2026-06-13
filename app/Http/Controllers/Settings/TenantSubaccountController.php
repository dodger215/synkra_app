<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TenantSubaccount;

class TenantSubaccountController extends Controller
{
    public function index()
    {
        $subaccount = Auth::user()->tenant->subaccounts()->first();

        // Dashboard Metrics (Ready for real DB hookups)
        $transactionsCount = $subaccount ? $subaccount->transactions()->count() : 0;
        $totalRevenue = $subaccount ? $subaccount->transactions()->sum('amount') : 0;
        $totalPayouts = $subaccount ? $subaccount->payouts()->sum('amount') : 0;

        return view('settings.subaccounts.index', compact('subaccount', 'transactionsCount', 'totalRevenue', 'totalPayouts'));
    }

    public function resolve(Request $request)
    {
        $request->validate([
            'account_number' => ['required', 'string'],
            'bank_code' => ['required', 'string'],
        ]);

        $response = \Illuminate\Support\Facades\Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/bank/resolve", [
                'account_number' => $request->account_number,
                'bank_code' => $request->bank_code,
            ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'account_name' => $response->json('data.account_name')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Could not verify account details.'
        ], 400);
    }

    public function store(Request $request)
    {
        $tenant = Auth::user()->tenant;

        $validated = $request->validate([
            'bank_code' => ['required', 'string', 'max:10'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:20'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'settlement_bank' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:3'],
            'settlement_schedule' => ['required', 'string', 'in:AUTO,MANUAL'],
        ]);

        $validated['percentage_charge'] = 0;

        $tenant->subaccounts()->create($validated);

        return back()->with('status', 'Subaccount added successfully.');
    }

    public function update(Request $request, $id)
    {
        $subaccount = Auth::user()->tenant->subaccounts()->findOrFail($id);

        $validated = $request->validate([
            'bank_code' => ['required', 'string', 'max:10'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:20'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'settlement_bank' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:3'],
            'settlement_schedule' => ['required', 'string', 'in:AUTO,MANUAL'],
            'is_active' => ['boolean'],
            'metadata' => ['nullable', 'array'],
        ]);

        $validated['percentage_charge'] = 0;

        $subaccount->update($validated);

        return back()->with('status', 'Subaccount updated successfully.');
    }

    public function destroy($id)
    {
        $subaccount = Auth::user()->tenant->subaccounts()->findOrFail($id);
        $subaccount->delete();

        return back()->with('status', 'Subaccount deleted.');
    }
}
