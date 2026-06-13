<?php

namespace App\Http\Controllers\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $suppliers = Supplier::where('tenant_id', $tenantId)->get();
        return view('supply_chain.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supply_chain.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_code' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        Supplier::create(array_merge($request->all(), ['tenant_id' => Auth::user()->tenant_id]));

        return redirect()->route('supply_chain.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $supplier = Supplier::with('purchaseOrders')->where('tenant_id', $tenantId)->findOrFail($id);
        return view('supply_chain.suppliers.show', compact('supplier'));
    }

    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $supplier = Supplier::where('tenant_id', $tenantId)->findOrFail($id);
        return view('supply_chain.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_code' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $supplier = Supplier::where('tenant_id', $tenantId)->findOrFail($id);
        $supplier->update($request->all());

        return redirect()->route('supply_chain.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $supplier = Supplier::where('tenant_id', $tenantId)->findOrFail($id);
        
        if ($supplier->purchaseOrders()->exists()) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete supplier with existing purchase orders.']);
        }
        
        $supplier->delete();
        return redirect()->route('supply_chain.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
