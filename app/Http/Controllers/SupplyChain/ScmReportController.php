<?php

namespace App\Http\Controllers\SupplyChain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\SupplierContract;
use App\Models\ReceivingReport;
use Illuminate\Support\Facades\Auth;
use App\Services\Export\CsvExporter;

class ScmReportController extends Controller
{
    public function index()
    {
        return view('supply_chain.reports.index');
    }

    public function exportSuppliers()
    {
        $tenantId = Auth::user()->tenant_id;
        $data = Supplier::where('tenant_id', $tenantId)->get();

        $columns = ['supplier_code', 'company_name', 'contact_person', 'email', 'phone', 'address', 'created_at'];
        $headers = ['Code', 'Company', 'Contact', 'Email', 'Phone', 'Address', 'Registered At'];

        return (new CsvExporter())->export($data, $columns, $headers, 'suppliers_report.csv');
    }

    public function exportPurchaseOrders()
    {
        $tenantId = Auth::user()->tenant_id;
        $data = PurchaseOrder::with('supplier')->where('tenant_id', $tenantId)->get();

        $columns = ['po_number', 'supplier.company_name', 'order_date', 'status', 'total_amount', 'delivery_status'];
        $headers = ['PO Number', 'Supplier', 'Order Date', 'Status', 'Total', 'Delivery Status'];

        return (new CsvExporter())->export($data, $columns, $headers, 'purchase_orders_report.csv');
    }
}
