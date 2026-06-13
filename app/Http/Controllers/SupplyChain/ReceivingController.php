<?php

namespace App\Http\Controllers\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReceivingReport;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class ReceivingController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $reports = ReceivingReport::with(['purchaseOrder', 'receiver'])->where('tenant_id', $tenantId)->get();
        return view('supply_chain.receiving.index', compact('reports'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $purchaseOrders = PurchaseOrder::where('tenant_id', $tenantId)->whereIn('status', ['approved', 'partially_received'])->get();
        return view('supply_chain.receiving.create', compact('purchaseOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'received_date' => 'required|date',
        ]);

        $report = ReceivingReport::create(array_merge($request->all(), [
            'tenant_id' => Auth::user()->tenant_id,
            'receipt_number' => 'RCV-' . strtoupper(uniqid()),
            'received_by' => Auth::id(),
            'status' => 'draft',
        ]));

        return redirect()->route('supply_chain.receiving.show', $report->id)->with('success', 'Receiving report created.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $report = ReceivingReport::with(['purchaseOrder.items', 'receiver'])
            ->where('tenant_id', $tenantId)->findOrFail($id);
        return view('supply_chain.receiving.show', compact('report'));
    }
}
