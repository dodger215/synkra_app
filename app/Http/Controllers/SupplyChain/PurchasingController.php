<?php

namespace App\Http\Controllers\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class PurchasingController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $purchaseOrders = PurchaseOrder::with(['supplier', 'creator'])->where('tenant_id', $tenantId)->get();
        return view('supply_chain.purchasing.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $suppliers = Supplier::where('tenant_id', $tenantId)->get();
        return view('supply_chain.purchasing.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
        ]);

        $po = PurchaseOrder::create(array_merge($request->all(), [
            'tenant_id' => Auth::user()->tenant_id,
            'po_number' => 'PO-' . strtoupper(uniqid()),
            'created_by' => Auth::id(),
            'status' => 'draft',
        ]));

        return redirect()->route('supply_chain.purchasing.show', $po->id)->with('success', 'Purchase Order created. Add items now.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $purchaseOrder = PurchaseOrder::with(['supplier', 'items.product', 'receivingReports'])
            ->where('tenant_id', $tenantId)->findOrFail($id);
        return view('supply_chain.purchasing.show', compact('purchaseOrder'));
    }

    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $purchaseOrder = PurchaseOrder::where('tenant_id', $tenantId)->findOrFail($id);
        $suppliers = Supplier::where('tenant_id', $tenantId)->get();
        return view('supply_chain.purchasing.edit', compact('purchaseOrder', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id;
        $po = PurchaseOrder::where('tenant_id', $tenantId)->findOrFail($id);
        $po->update($request->all());

        return redirect()->route('supply_chain.purchasing.show', $po->id)->with('success', 'Purchase Order updated.');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $po = PurchaseOrder::where('tenant_id', $tenantId)->findOrFail($id);
        $po->delete();

        return redirect()->route('supply_chain.purchasing.index')->with('success', 'Purchase Order deleted.');
    }

    public function approve($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $po = PurchaseOrder::where('tenant_id', $tenantId)->findOrFail($id);
        $po->update(['status' => 'approved', 'approved_by' => Auth::id(), 'approved_at' => now()]);

        return redirect()->back()->with('success', 'Purchase Order approved.');
    }

    public function cancel($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $po = PurchaseOrder::where('tenant_id', $tenantId)->findOrFail($id);
        $po->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Purchase Order cancelled.');
    }
}
