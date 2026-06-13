<?php

namespace App\Http\Controllers\ProductService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckPermission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

// Export services
use App\Services\Export\CsvExporter;
use App\Services\Export\GenericExport;
use App\Services\Export\MultiSheetExport;
use App\Services\Export\GoogleSheetsExporter;
use Maatwebsite\Excel\Facades\Excel;

// Models
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockTransfer;
use App\Models\StockBalance;
use App\Models\StockBin;
use App\Models\StockCount;
use App\Models\StockDamage;
use App\Models\StockLocation;
use App\Models\StockMovement;
use App\Models\StockReturn;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\PosSession;

class ExportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(CheckPermission::class . ':product_service,view'),
        ];
    }

    // ────────────────────────────────────────────────
    //  Column definitions (reusable across formats)
    // ────────────────────────────────────────────────

    protected function productColumns(): array
    {
        return [
            'columns' => [
                'id', 'sku', 'barcode', 'name', 'description', 'category.name',
                'brand', 'unit_type', 'unit_price', 'cost_price', 'weight_kg',
                'min_stock_level', 'max_stock_level', 'reorder_point',
                'reorder_quantity', 'is_active', 'tax_rate', 'created_at',
            ],
            'headers' => [
                'ID', 'SKU', 'Barcode', 'Name', 'Description', 'Category',
                'Brand', 'Unit', 'Unit Price', 'Cost Price', 'Weight (kg)',
                'Min Stock', 'Max Stock', 'Reorder Point',
                'Reorder Qty', 'Active', 'Tax Rate (%)', 'Created At',
            ],
        ];
    }

    protected function stockBalanceColumns(): array
    {
        return [
            'columns' => [
                'product.name', 'product.sku', 'location.name', 'bin.name',
                'quantity_on_hand', 'quantity_reserved', 'quantity_in_transit',
                'quantity_damaged', 'quantity_returned', 'last_counted_at', 'updated_at',
            ],
            'headers' => [
                'Product', 'SKU', 'Location', 'Bin',
                'On Hand', 'Reserved', 'In Transit',
                'Damaged', 'Returned', 'Last Counted', 'Updated At',
            ],
        ];
    }

    protected function stockMovementColumns(): array
    {
        return [
            'columns' => [
                'id', 'product.name', 'location.name', 'movement_type',
                'quantity', 'previous_balance', 'new_balance',
                'notes', 'creator.name', 'created_at',
            ],
            'headers' => [
                'ID', 'Product', 'Location', 'Type',
                'Quantity', 'Previous Balance', 'New Balance',
                'Notes', 'Created By', 'Date',
            ],
        ];
    }

    protected function stockAdjustmentColumns(): array
    {
        return [
            'columns' => [
                'id', 'adjustment_number', 'product.name', 'location.name',
                'adjustment_type', 'quantity', 'reason.name',
                'notes', 'creator.name', 'status', 'created_at',
            ],
            'headers' => [
                'ID', 'Adjustment #', 'Product', 'Location',
                'Type', 'Quantity', 'Reason',
                'Notes', 'Created By', 'Status', 'Date',
            ],
        ];
    }

    protected function stockTransferColumns(): array
    {
        return [
            'columns' => [
                'id', 'transfer_number', 'product.name',
                'fromLocation.name', 'toLocation.name',
                'quantity', 'notes', 'creator.name', 'status', 'created_at',
            ],
            'headers' => [
                'ID', 'Transfer #', 'Product',
                'From Location', 'To Location',
                'Quantity', 'Notes', 'Created By', 'Status', 'Date',
            ],
        ];
    }

    protected function stockDamageColumns(): array
    {
        return [
            'columns' => [
                'id', 'damage_number', 'product.name', 'location.name',
                'quantity', 'damage_type', 'severity', 'disposed_quantity',
                'report_notes', 'reporter.name', 'reported_at', 'status',
            ],
            'headers' => [
                'ID', 'Damage #', 'Product', 'Location',
                'Quantity', 'Type', 'Severity', 'Disposed',
                'Notes', 'Reported By', 'Reported At', 'Status',
            ],
        ];
    }

    protected function stockCountColumns(): array
    {
        return [
            'columns' => [
                'id', 'count_number', 'product.name', 'location.name',
                'expected_quantity', 'counted_quantity', 'variance_percentage',
                'counter.name', 'counted_at', 'notes', 'status',
            ],
            'headers' => [
                'ID', 'Count #', 'Product', 'Location',
                'Expected', 'Counted', 'Variance %',
                'Counted By', 'Counted At', 'Notes', 'Status',
            ],
        ];
    }

    protected function stockReturnColumns(): array
    {
        return [
            'columns' => [
                'id', 'return_number', 'product.name', 'location.name',
                'quantity', 'return_reason', 'condition',
                'restocked_quantity', 'refund_amount', 'creator.name', 'status', 'created_at',
            ],
            'headers' => [
                'ID', 'Return #', 'Product', 'Location',
                'Quantity', 'Reason', 'Condition',
                'Restocked', 'Refund Amount', 'Created By', 'Status', 'Date',
            ],
        ];
    }

    protected function stockLocationColumns(): array
    {
        return [
            'columns' => ['id', 'name', 'address', 'type', 'is_active', 'created_at'],
            'headers' => ['ID', 'Name', 'Address', 'Type', 'Active', 'Created At'],
        ];
    }

    protected function stockBinColumns(): array
    {
        return [
            'columns' => ['id', 'name', 'location.name', 'barcode', 'capacity', 'is_active'],
            'headers' => ['ID', 'Name', 'Location', 'Barcode', 'Capacity', 'Active'],
        ];
    }

    protected function posOrderColumns(): array
    {
        return [
            'columns' => [
                'id', 'order_number', 'customer.name', 'session.cashier.name',
                'order_type', 'subtotal', 'discount_amount', 'tax_amount',
                'total_amount', 'paid_amount', 'change_amount',
                'payment_status', 'payment_method', 'completed_at',
            ],
            'headers' => [
                'ID', 'Order #', 'Customer', 'Cashier',
                'Type', 'Subtotal', 'Discount', 'Tax',
                'Total', 'Paid', 'Change',
                'Payment Status', 'Payment Method', 'Completed At',
            ],
        ];
    }

    protected function posSessionColumns(): array
    {
        return [
            'columns' => [
                'id', 'device.device_name', 'cashier.name',
                'started_at', 'ended_at', 'opening_balance', 'closing_balance',
                'cash_sales', 'card_sales', 'expected_cash', 'actual_cash', 'variance', 'status',
            ],
            'headers' => [
                'ID', 'Device', 'Cashier',
                'Started At', 'Ended At', 'Opening Balance', 'Closing Balance',
                'Cash Sales', 'Card Sales', 'Expected Cash', 'Actual Cash', 'Variance', 'Status',
            ],
        ];
    }

    // ────────────────────────────────────────────────
    //  Core dispatch: format → csv | xlsx | google
    // ────────────────────────────────────────────────

    protected function dispatch(Request $request, $data, array $columns, array $headers, string $baseFilename, string $sheetTitle = 'Sheet1')
    {
        $format = $request->query('format', 'csv');

        switch ($format) {
            case 'xlsx':
            case 'excel':
                $export = new GenericExport($data, $columns, $headers, $sheetTitle);
                return Excel::download($export, $baseFilename . '.xlsx');

            case 'google':
            case 'google_sheets':
                $exporter = new GoogleSheetsExporter();
                try {
                    $url = $exporter->export($data, $columns, $headers, $sheetTitle);
                    return redirect()->back()->with('success', "Exported to Google Sheets: {$url}");
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors(['export' => $e->getMessage()]);
                }

            case 'csv':
            default:
                $csvExporter = new CsvExporter();
                return $csvExporter->export($data, $columns, $headers, $baseFilename . '.csv');
        }
    }

    // ────────────────────────────────────────────────
    //  Product Exports
    // ────────────────────────────────────────────────

    public function products(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = Product::with('category')->where('tenant_id', $tenantId)->get();
        $def = $this->productColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'products', 'Products');
    }

    // ────────────────────────────────────────────────
    //  Stock Exports
    // ────────────────────────────────────────────────

    public function stockBalances(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockBalance::with(['product', 'location', 'bin'])
            ->whereHas('product', fn($q) => $q->where('tenant_id', $tenantId))
            ->get();
        $def = $this->stockBalanceColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_balances', 'Stock Balances');
    }

    public function stockMovements(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockMovement::with(['product', 'location', 'creator'])
            ->where('tenant_id', $tenantId)->get();
        $def = $this->stockMovementColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_movements', 'Stock Movements');
    }

    public function stockAdjustments(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockAdjustment::with(['product', 'location', 'reason', 'creator'])
            ->where('tenant_id', $tenantId)->get();
        $def = $this->stockAdjustmentColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_adjustments', 'Stock Adjustments');
    }

    public function stockTransfers(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockTransfer::with(['product', 'fromLocation', 'toLocation', 'creator'])
            ->where('tenant_id', $tenantId)->get();
        $def = $this->stockTransferColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_transfers', 'Stock Transfers');
    }

    public function stockDamages(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockDamage::with(['product', 'location', 'reporter'])
            ->where('tenant_id', $tenantId)->get();
        $def = $this->stockDamageColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_damages', 'Stock Damages');
    }

    public function stockCounts(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockCount::with(['product', 'location', 'counter'])
            ->where('tenant_id', $tenantId)->get();
        $def = $this->stockCountColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_counts', 'Stock Counts');
    }

    public function stockReturns(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockReturn::with(['product', 'location', 'creator'])
            ->where('tenant_id', $tenantId)->get();
        $def = $this->stockReturnColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_returns', 'Stock Returns');
    }

    public function stockLocations(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockLocation::where('tenant_id', $tenantId)->get();
        $def = $this->stockLocationColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_locations', 'Locations');
    }

    public function stockBins(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = StockBin::with('location')->where('tenant_id', $tenantId)->get();
        $def = $this->stockBinColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'stock_bins', 'Bins');
    }

    // ────────────────────────────────────────────────
    //  POS Exports
    // ────────────────────────────────────────────────

    public function posOrders(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = PosOrder::with(['customer', 'session.cashier'])
            ->where('tenant_id', $tenantId)->get();
        $def = $this->posOrderColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'pos_orders', 'POS Orders');
    }

    public function posSessions(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $data = PosSession::with(['device', 'cashier'])
            ->where('tenant_id', $tenantId)->get();
        $def = $this->posSessionColumns();
        return $this->dispatch($request, $data, $def['columns'], $def['headers'], 'pos_sessions', 'POS Sessions');
    }

    // ────────────────────────────────────────────────
    //  Merged / Combined Exports
    // ────────────────────────────────────────────────

    /**
     * Export ALL stock-related data into one file.
     * xlsx → each model gets its own sheet tab
     * csv  → combined into one CSV with a "Section" column
     * google → creates one Google Sheet with multiple tabs
     */
    public function allStocks(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $format = $request->query('format', 'csv');

        // Gather all datasets
        $datasets = [
            'Balances'    => ['data' => StockBalance::with(['product','location','bin'])->whereHas('product', fn($q) => $q->where('tenant_id', $tenantId))->get(), 'def' => $this->stockBalanceColumns()],
            'Movements'   => ['data' => StockMovement::with(['product','location','creator'])->where('tenant_id', $tenantId)->get(), 'def' => $this->stockMovementColumns()],
            'Adjustments' => ['data' => StockAdjustment::with(['product','location','reason','creator'])->where('tenant_id', $tenantId)->get(), 'def' => $this->stockAdjustmentColumns()],
            'Transfers'   => ['data' => StockTransfer::with(['product','fromLocation','toLocation','creator'])->where('tenant_id', $tenantId)->get(), 'def' => $this->stockTransferColumns()],
            'Damages'     => ['data' => StockDamage::with(['product','location','reporter'])->where('tenant_id', $tenantId)->get(), 'def' => $this->stockDamageColumns()],
            'Counts'      => ['data' => StockCount::with(['product','location','counter'])->where('tenant_id', $tenantId)->get(), 'def' => $this->stockCountColumns()],
            'Returns'     => ['data' => StockReturn::with(['product','location','creator'])->where('tenant_id', $tenantId)->get(), 'def' => $this->stockReturnColumns()],
            'Locations'   => ['data' => StockLocation::where('tenant_id', $tenantId)->get(), 'def' => $this->stockLocationColumns()],
            'Bins'        => ['data' => StockBin::with('location')->where('tenant_id', $tenantId)->get(), 'def' => $this->stockBinColumns()],
        ];

        if (in_array($format, ['xlsx', 'excel'])) {
            $sheets = [];
            foreach ($datasets as $title => $set) {
                $sheets[] = new GenericExport($set['data'], $set['def']['columns'], $set['def']['headers'], $title);
            }
            return Excel::download(new MultiSheetExport($sheets), 'all_stocks.xlsx');
        }

        if (in_array($format, ['google', 'google_sheets'])) {
            $exporter = new GoogleSheetsExporter();
            try {
                // Export first dataset as a new sheet, append the rest
                $first = true;
                $spreadsheetUrl = '';
                $spreadsheetId = '';
                foreach ($datasets as $title => $set) {
                    if ($first) {
                        $spreadsheetUrl = $exporter->export($set['data'], $set['def']['columns'], $set['def']['headers'], 'All Stocks');
                        preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $spreadsheetUrl, $matches);
                        $spreadsheetId = $matches[1] ?? '';
                        $first = false;
                    } elseif ($spreadsheetId) {
                        $exporter->appendTo($spreadsheetId, $set['data'], $set['def']['columns'], $title);
                    }
                }
                return redirect()->back()->with('success', "Exported to Google Sheets: {$spreadsheetUrl}");
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['export' => $e->getMessage()]);
            }
        }

        // CSV: merge all into one file with a "Section" prefix column
        $csvExporter = new CsvExporter();
        $merged = collect();
        foreach ($datasets as $title => $set) {
            foreach ($set['data'] as $row) {
                $line = new \stdClass();
                $line->_section = $title;
                foreach ($set['def']['columns'] as $col) {
                    $line->{$col} = data_get($row, $col);
                }
                $merged->push($line);
            }
        }
        $allColumns = array_merge(['_section'], $this->stockBalanceColumns()['columns']);
        $allHeaders = array_merge(['Section'], $this->stockBalanceColumns()['headers']);
        return $csvExporter->export($merged, $allColumns, $allHeaders, 'all_stocks.csv');
    }

    /**
     * Export ALL POS data (orders + sessions) into one file.
     */
    public function allPos(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $format = $request->query('format', 'csv');

        $orderData = PosOrder::with(['customer','session.cashier'])->where('tenant_id', $tenantId)->get();
        $sessionData = PosSession::with(['device','cashier'])->where('tenant_id', $tenantId)->get();

        $orderDef = $this->posOrderColumns();
        $sessionDef = $this->posSessionColumns();

        if (in_array($format, ['xlsx', 'excel'])) {
            $sheets = [
                new GenericExport($orderData, $orderDef['columns'], $orderDef['headers'], 'Orders'),
                new GenericExport($sessionData, $sessionDef['columns'], $sessionDef['headers'], 'Sessions'),
            ];
            return Excel::download(new MultiSheetExport($sheets), 'all_pos.xlsx');
        }

        if (in_array($format, ['google', 'google_sheets'])) {
            $exporter = new GoogleSheetsExporter();
            try {
                $url = $exporter->export($orderData, $orderDef['columns'], $orderDef['headers'], 'All POS');
                return redirect()->back()->with('success', "Exported to Google Sheets: {$url}");
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['export' => $e->getMessage()]);
            }
        }

        // CSV fallback: export orders (primary dataset)
        return (new CsvExporter())->export($orderData, $orderDef['columns'], $orderDef['headers'], 'all_pos_orders.csv');
    }
}

