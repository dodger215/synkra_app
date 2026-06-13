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
        $subaccounts = Auth::user()->tenant->subaccounts;
        return view('settings.subaccounts.index', compact('subaccounts'));
    }

    public function store(Request $request)
    {
        $tenant = Auth::user()->tenant;

        $validated = $request->validate([
            'bank_code' => ['required', 'string', 'max:10'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:20'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'percentage_charge' => ['required', 'numeric', 'min:0', 'max:100'],
            'settlement_bank' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:3'],
            'settlement_schedule' => ['required', 'string', 'in:AUTO,MANUAL'],
        ]);

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
            'percentage_charge' => ['required', 'numeric', 'min:0', 'max:100'],
            'settlement_bank' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:3'],
            'settlement_schedule' => ['required', 'string', 'in:AUTO,MANUAL'],
            'is_active' => ['boolean'],
            'metadata' => ['nullable', 'array'],
        ]);

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
