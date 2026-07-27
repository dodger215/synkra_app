<?php

namespace App\Http\Controllers\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\Product;
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
        $networkSuppliers = Tenant::where(function($q) {
                $q->where('supply_chain_mode', 'supplier')
                  ->orWhere('supply_chain_mode', 'both');
            })
            ->where('id', '!=', Auth::user()->tenant_id)
            ->with('users')
            ->get();

        return view('supply_chain.suppliers.create', compact('networkSuppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_type' => 'required|in:manual,network',
            'supplier_tenant_id' => 'required_if:supplier_type,network|nullable|exists:tenants,id',
            'supplier_code' => 'required|string|max:255',
            'company_name' => 'required_if:supplier_type,manual|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['tenant_id'] = Auth::user()->tenant_id;

        if ($request->supplier_type === 'network') {
            $targetTenant = Tenant::findOrFail($request->supplier_tenant_id);
            $data['company_name'] = $targetTenant->name;
            $data['connection_status'] = 'pending';
            $data['is_active'] = false;
        } else {
            $data['connection_status'] = 'approved';
            $data['is_active'] = true;
        }

        Supplier::create($data);

        return redirect()->route('supply_chain.suppliers.index')->with('success', $request->supplier_type === 'network' ? 'Connection request sent to supplier.' : 'Supplier created successfully.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $supplier = Supplier::with(['purchaseOrders', 'products.reorderAlerts'])
            ->where('tenant_id', $tenantId)
            ->findOrFail($id);

        $unlinkedProducts = Product::where('tenant_id', $tenantId)
            ->whereNull('supplier_id')
            ->get();

        return view('supply_chain.suppliers.show', compact('supplier', 'unlinkedProducts'));
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

    // Supplier Mode Methods
    public function supplierList()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)
            ->where('is_network_available', true)
            ->get();
        return view('supply_chain.suppliers.supplier_list', compact('products'));
    }

    public function importStocks()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)->with('stockBalances')->get();
        return view('supply_chain.suppliers.import_stocks', compact('products'));
    }

    public function processImportStocks(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Importing stock request', $request->all());

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supply_price' => 'required|numeric|min:0',
            'supply_min_order' => 'required|numeric|min:1',
            'supply_max_order' => 'nullable|numeric|min:1',
            'supply_buffer_percent' => 'required|numeric|min:0|max:100',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $product = Product::where('tenant_id', $tenantId)->findOrFail($request->product_id);

        $product->update([
            'is_network_available' => true,
            'supply_price' => $request->supply_price,
            'supply_min_order' => $request->supply_min_order,
            'supply_max_order' => $request->supply_max_order,
            'supply_buffer_percent' => $request->supply_buffer_percent,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "{$product->name} is now available in the flowexa Network."]);
        }

        return redirect()->back()->with('success', "{$product->name} supply configuration updated successfully.");
    }

    public function removeStockFromSupply($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $product = Product::where('tenant_id', $tenantId)->findOrFail($id);

        $product->update(['is_network_available' => false]);

        return redirect()->back()->with('success', "{$product->name} has been removed from the flowexa Network.");
    }

    public function approvals()
    {
        $tenantId = Auth::user()->tenant_id;
        $incomingRequests = Supplier::where('supplier_tenant_id', $tenantId)
            ->where('connection_status', 'pending')
            ->with('tenant')
            ->get();

        return view('supply_chain.suppliers.approvals', compact('incomingRequests'));
    }

    public function approveRequest($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $request = Supplier::where('supplier_tenant_id', $tenantId)
            ->with(['tenant.users', 'supplierTenant'])
            ->findOrFail($id);

        $request->update(['connection_status' => 'approved', 'is_active' => true]);

        // Notify
        $this->notifyPartner($request, 'approved');

        return redirect()->back()->with('success', 'Supplier request approved.');
    }

    public function rejectRequest(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $tenantId = Auth::user()->tenant_id;
        $supplierReq = Supplier::where('supplier_tenant_id', $tenantId)
            ->with(['tenant.users', 'supplierTenant'])
            ->findOrFail($id);

        $supplierReq->update([
            'connection_status' => 'rejected',
            'is_active' => false,
            'rejection_reason' => $request->reason
        ]);

        // Notify
        $this->notifyPartner($supplierReq, 'rejected', $request->reason);

        return redirect()->back()->with('success', 'Supplier request rejected.');
    }

    private function notifyPartner(Supplier $supplier, string $status, ?string $reason = null)
    {
        $partnerTenant = $supplier->tenant;
        $primaryUser = $partnerTenant->users()->first(); // assuming first user is the one to notify

        if (!$primaryUser) return;

        // 1. Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($primaryUser->email)
                ->send(new \App\Mail\SupplierConnectionStatusMail($supplier, $status, $reason));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send supplier status email: " . $e->getMessage());
        }

        // 2. Send SMS via Arkesel
        if ($primaryUser->phone_number) {
            $message = "flowexa: Your connection request to {$supplier->supplierTenant->name} has been {$status}.";
            if ($status === 'rejected' && $reason) {
                $message .= " Reason: " . substr($reason, 0, 50);
            }

            try {
                \Illuminate\Support\Facades\Http::withHeaders([
                    'api-key' => config('services.arkesel.api_key'),
                ])->post('https://sms.arkesel.com/api/v2/sms/send', [
                    'sender' => 'flowexa',
                    'message' => $message,
                    'recipients' => [$primaryUser->phone_number],
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Arkesel SMS Failed for Supplier Notification: " . $e->getMessage());
            }
        }
    }

    public function linkProduct(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $supplier = Supplier::where('tenant_id', $tenantId)->findOrFail($id);
        $product = Product::where('tenant_id', $tenantId)->findOrFail($request->product_id);

        $product->update(['supplier_id' => $supplier->id]);

        return redirect()->back()->with('success', "Product '{$product->name}' linked to supplier.");
    }
}
