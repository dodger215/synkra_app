<?php

namespace App\Http\Controllers\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierContract;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierContractController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $contracts = SupplierContract::with('supplier')
            ->whereHas('supplier', function($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })->get();
        return view('supply_chain.contracts.index', compact('contracts'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $suppliers = Supplier::where('tenant_id', $tenantId)->get();
        return view('supply_chain.contracts.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_number' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        if ($request->has('terms') && is_string($request->terms)) {
            $data['terms'] = array_filter(explode("\n", str_replace("\r", "", $request->terms)));
        }

        SupplierContract::create($data);

        return redirect()->route('supply_chain.contracts.index')->with('success', 'Contract created successfully.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $contract = SupplierContract::with('supplier')
            ->whereHas('supplier', function($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })->findOrFail($id);
        return view('supply_chain.contracts.show', compact('contract'));
    }

    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $contract = SupplierContract::with('supplier')
            ->whereHas('supplier', function($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })->findOrFail($id);
        $suppliers = Supplier::where('tenant_id', $tenantId)->get();
        return view('supply_chain.contracts.edit', compact('contract', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_number' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $contract = SupplierContract::whereHas('supplier', function($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        })->findOrFail($id);

        $data = $request->all();
        if ($request->has('terms') && is_string($request->terms)) {
            $data['terms'] = array_filter(explode("\n", str_replace("\r", "", $request->terms)));
        }

        $contract->update($data);

        return redirect()->route('supply_chain.contracts.index')->with('success', 'Contract updated successfully.');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $contract = SupplierContract::whereHas('supplier', function($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        })->findOrFail($id);

        $contract->delete();
        return redirect()->route('supply_chain.contracts.index')->with('success', 'Contract deleted successfully.');
    }
}
