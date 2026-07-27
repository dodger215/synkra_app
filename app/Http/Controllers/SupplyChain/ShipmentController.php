<?php

namespace App\Http\Controllers\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        // Shipments in this context are POs that are approved and pending delivery or partially received
        $shipments = PurchaseOrder::with(['supplier'])
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['approved', 'partially_received', 'shipped'])
            ->orderBy('expected_delivery_date', 'asc')
            ->get();

        return view('supply_chain.shipments.index', compact('shipments'));
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $shipment = PurchaseOrder::with(['supplier', 'items.product', 'receivingReports'])
            ->where('tenant_id', $tenantId)
            ->findOrFail($id);

        return view('supply_chain.shipments.show', compact('shipment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['delivery_status' => 'required|string']);

        $tenantId = Auth::user()->tenant_id;
        $po = PurchaseOrder::where('tenant_id', $tenantId)->findOrFail($id);
        $po->update(['delivery_status' => $request->delivery_status]);

        return redirect()->back()->with('success', 'Shipment status updated.');
    }
}
