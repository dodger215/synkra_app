<?php

namespace App\Http\Controllers\ProductService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
// use App\Models\ReorderAlert;
use App\Models\StockMovement;
use App\Models\StockAdjustment;
use App\Models\StockTransfer;
use App\Models\StockAdjustmentReason;
use App\Models\StockBalance;
use App\Models\StockBin;
use App\Models\StockCount;
use App\Models\StockCountSchedule;
use App\Models\StockDamage;
use App\Models\StockLocation;
use App\Models\StockMovementType;
use App\Models\StockReturn;
use App\Models\ReorderAlert;


class StocksManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(CheckPermission::class . ':product_service,view', only: [
                'index', 'movements', 'adjustments', 'transfers', 'damages', 'counts', 'returns',
                'reorderAlerts', 'show', 'createReceive', 'createIssue',
                'createTransfer', 'createAdjustment', 'createDamage', 'createCount',
                'createReturn', 'bins', 'locations'
            ]),
            new Middleware(CheckPermission::class . ':product_service,update', only: [
                'storeAdjustment', 'storeTransfer', 'storeReceive', 'storeIssue',
                'storeDamage', 'storeCount', 'storeReturn', 'storeBin',
                'storeLocation', 'updateLocation', 'destroyLocation', 'importStock',
                'resolveReorderAlert', 'storeAdjustmentReason'
            ]),
        ];
    }

    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $balances = StockBalance::with(['product', 'location', 'bin'])
            ->whereHas('product', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })->get();

        $locations = StockLocation::where('tenant_id', $tenantId)->where('is_active', true)->get();
        $products = Product::with('category')->where('tenant_id', $tenantId)->get();
        $reasons = StockAdjustmentReason::where('tenant_id', $tenantId)->get();
        $stockDetailsByProduct = $this->buildStockDetailsByProduct($tenantId, $products, $balances);

        return view('product_service.stocks.index', compact(
            'balances', 'locations', 'products', 'reasons', 'stockDetailsByProduct'
        ));
    }


    public function importStock(Request $request, $productId)
    {
        $request->validate([
            'stock_location_id' => 'required|exists:stock_locations,id',
            'stock_quantity'    => 'required|numeric|min:0.01',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $product = Product::where('tenant_id', $tenantId)->findOrFail($productId);

        $location = StockLocation::where('tenant_id', $tenantId)->findOrFail($request->stock_location_id);

        if (StockBalance::where('product_id', $product->id)->exists()) {
            return redirect()->back()->with('error', 'This product is already in stock.');
        }

        DB::transaction(function () use ($request, $product, $location) {
            $tenantId = Auth::user()->tenant_id;
            $userId   = Auth::id();
            $qty      = abs($request->stock_quantity);

            $balance = StockBalance::firstOrCreate(
                ['product_id' => $product->id, 'location_id' => $location->id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            $oldQty = $balance->quantity_on_hand;
            $newQty = $oldQty + $qty;

            $movementType = StockMovementType::where('name', 'receive')->first();

            StockMovement::create([
                'tenant_id'        => $tenantId,
                'product_id'       => $product->id,
                'location_id'      => $location->id,
                'movement_type_id' => $movementType?->id,
                'movement_type'    => 'receive',
                'quantity'         => $qty,
                'previous_balance' => $oldQty,
                'new_balance'      => $newQty,
                'notes'            => 'Stock import from stocks index.',
                'created_by'       => $userId,
                'approved_by'      => $userId,
                'approved_at'      => now(),
            ]);

            $balance->update(['quantity_on_hand' => $newQty]);
        });

        return redirect()
            ->route('product_service.stocks.index')
            ->with('success', "'{$product->name}' imported to {$location->name} successfully.");
    }

    public function movements()
    {
        $tenantId = Auth::user()->tenant_id;

        $movements = StockMovement::with(['product', 'location', 'creator'])
            ->where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('product_service.stocks.movements', compact('movements'));
    }

    public function adjustments()
    {
        $tenantId = Auth::user()->tenant_id;

        $adjustments = StockAdjustment::with(['product', 'location', 'reason', 'approver'])
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(50);

        return view('product_service.stocks.adjustments', compact('adjustments'));
    }

    public function transfers()
    {
        $tenantId = Auth::user()->tenant_id;

        $transfers = StockTransfer::with(['product', 'fromLocation', 'toLocation'])
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(50);

        return view('product_service.stocks.transfers', compact('transfers'));
    }

    public function damages()
    {
        $tenantId = Auth::user()->tenant_id;

        $damages = StockDamage::with(['product', 'location', 'reporter'])
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(50);

        return view('product_service.stocks.damages', compact('damages'));
    }

    public function counts()
    {
        $tenantId = Auth::user()->tenant_id;

        $counts = StockCount::with(['product', 'location', 'schedule', 'counter'])
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(50);

        return view('product_service.stocks.counts', compact('counts'));
    }

    public function returns()
    {
        $tenantId = Auth::user()->tenant_id;

        $returns = StockReturn::with(['product', 'location', 'creator'])
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(50);

        return view('product_service.stocks.returns', compact('returns'));
    }

    public function reorderAlerts()
    {
        $tenantId = Auth::user()->tenant_id;

        $lowStockBalances = StockBalance::with(['product', 'location'])
            ->whereHas('product', fn ($query) => $query->where('tenant_id', $tenantId))
            ->get()
            ->filter(fn (StockBalance $balance) => in_array($balance->reorder_status, ['critical', 'low'], true))
            ->values();

        $alerts = ReorderAlert::with(['product', 'location'])
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('product_service.stocks.reorder-alerts', compact('lowStockBalances', 'alerts'));
    }

    public function resolveReorderAlert($id)
    {
        $tenantId = Auth::user()->tenant_id;

        $alert = ReorderAlert::where('tenant_id', $tenantId)->findOrFail($id);
        $alert->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return redirect()
            ->route('product_service.stocks.reorder_alerts.index')
            ->with('success', 'Reorder alert resolved.');
    }

    public function storeAdjustment(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:stock_locations,id',
            'reason_id' => 'nullable|exists:stock_adjustment_reasons,id',
            'quantity_change' => 'required|numeric',
            'adjustment_type' => 'required|string|in:increase,decrease',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $tenantId = Auth::user()->tenant_id;
            $userId = Auth::id();

            // Find or create balance
            $balance = StockBalance::firstOrCreate(
                ['product_id' => $request->product_id, 'location_id' => $request->location_id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            $oldQuantity = $balance->quantity_on_hand;
            $change = abs($request->quantity_change);

            if ($request->adjustment_type === 'decrease') {
                $newQuantity = $oldQuantity - $change;
                $change = -$change; // make it negative for the movement record
            } else {
                $newQuantity = $oldQuantity + $change;
            }

            // Create Adjustment Record
            $adjustment = StockAdjustment::create([
                'tenant_id' => $tenantId,
                'adjustment_number' => 'ADJ-' . strtoupper(uniqid()),
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
                'reason_id' => $request->reason_id,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => $change,
                'adjustment_type' => $request->adjustment_type,
                'status' => 'approved',
                'notes' => $request->notes,
                'requested_by' => $userId,
                'requested_at' => now(),
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            // Create Movement
            $movementType = StockMovementType::where('name', 'adjustment')->first();
            StockMovement::create([
                'tenant_id' => $tenantId,
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
                'movement_type_id' => $movementType ? $movementType->id : null,
                'movement_type' => 'adjustment',
                'quantity' => $change,
                'previous_balance' => $oldQuantity,
                'new_balance' => $newQuantity,
                'reference_type' => StockAdjustment::class,
                'reference_id' => $adjustment->id,
                'notes' => $request->notes,
                'created_by' => $userId,
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            // Update Balance
            $balance->update(['quantity_on_hand' => $newQuantity]);
        });

        return redirect()->back()->with('success', 'Stock adjustment successful.');
    }

    public function storeAdjustmentReason(Request $request)
    {
        $request->validate([
            'reason_name' => 'required|string|max:255',
            'reason_code' => 'nullable|string|max:50',
            'adjustment_type' => 'nullable|string|in:increase,decrease',
            'category' => 'nullable|string|max:50',
        ]);

        $tenantId = Auth::user()->tenant_id;

        $reason = StockAdjustmentReason::create([
            'tenant_id' => $tenantId,
            'reason_code' => $request->reason_code ?: strtoupper('RSN-' . substr(uniqid(), -6)),
            'reason_name' => $request->reason_name,
            'adjustment_type' => $request->adjustment_type,
            'category' => $request->category,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Reason '{$reason->reason_name}' created successfully.",
                'reason' => [
                    'id' => $reason->id,
                    'reason_name' => $reason->reason_name,
                    'adjustment_type' => $reason->adjustment_type,
                ],
            ], 201);
        }

        return redirect()
            ->back()
            ->with('success', "Adjustment reason '{$reason->reason_name}' created successfully.");
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_location_id' => 'required|exists:stock_locations,id',
            'to_location_id' => 'required|exists:stock_locations,id|different:from_location_id',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $tenantId = Auth::user()->tenant_id;
            $userId = Auth::id();
            $quantity = abs($request->quantity);

            // Fetch Balances
            $fromBalance = StockBalance::firstOrCreate(
                ['product_id' => $request->product_id, 'location_id' => $request->from_location_id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            $toBalance = StockBalance::firstOrCreate(
                ['product_id' => $request->product_id, 'location_id' => $request->to_location_id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            $oldFrom = $fromBalance->quantity_on_hand;
            $newFrom = $oldFrom - $quantity;

            $oldTo = $toBalance->quantity_on_hand;
            $newTo = $oldTo + $quantity;

            // Create Transfer Record
            $transfer = StockTransfer::create([
                'tenant_id' => $tenantId,
                'transfer_number' => 'TRF-' . strtoupper(uniqid()),
                'product_id' => $request->product_id,
                'from_location_id' => $request->from_location_id,
                'to_location_id' => $request->to_location_id,
                'quantity' => $quantity,
                'status' => 'completed',
                'requested_by' => $userId,
                'requested_at' => now(),
                'shipped_by' => $userId,
                'shipped_at' => now(),
                'received_by' => $userId,
                'received_at' => now(),
                'notes' => $request->notes,
            ]);

            $movementTypeTransferOut = StockMovementType::where('name', 'transfer_out')->first();
            $movementTypeTransferIn = StockMovementType::where('name', 'transfer_in')->first();

            // Transfer Out Movement
            StockMovement::create([
                'tenant_id' => $tenantId,
                'product_id' => $request->product_id,
                'location_id' => $request->from_location_id,
                'movement_type_id' => $movementTypeTransferOut ? $movementTypeTransferOut->id : null,
                'movement_type' => 'transfer_out',
                'quantity' => -$quantity,
                'previous_balance' => $oldFrom,
                'new_balance' => $newFrom,
                'reference_type' => StockTransfer::class,
                'reference_id' => $transfer->id,
                'notes' => $request->notes,
                'created_by' => $userId,
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            // Transfer In Movement
            StockMovement::create([
                'tenant_id' => $tenantId,
                'product_id' => $request->product_id,
                'location_id' => $request->to_location_id,
                'movement_type_id' => $movementTypeTransferIn ? $movementTypeTransferIn->id : null,
                'movement_type' => 'transfer_in',
                'quantity' => $quantity,
                'previous_balance' => $oldTo,
                'new_balance' => $newTo,
                'reference_type' => StockTransfer::class,
                'reference_id' => $transfer->id,
                'notes' => $request->notes,
                'created_by' => $userId,
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            // Update Balances
            $fromBalance->update(['quantity_on_hand' => $newFrom]);
            $toBalance->update(['quantity_on_hand' => $newTo]);
        });

        return redirect()->back()->with('success', 'Stock transfer successful.');
    }
    public function storeReceive(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:stock_locations,id',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $tenantId = Auth::user()->tenant_id;
            $userId = Auth::id();
            $quantity = abs($request->quantity);

            $balance = StockBalance::firstOrCreate(
                ['product_id' => $request->product_id, 'location_id' => $request->location_id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            $oldQuantity = $balance->quantity_on_hand;
            $newQuantity = $oldQuantity + $quantity;

            $movementType = StockMovementType::where('name', 'receive')->first();

            StockMovement::create([
                'tenant_id' => $tenantId,
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
                'movement_type_id' => $movementType ? $movementType->id : null,
                'movement_type' => 'receive',
                'quantity' => $quantity,
                'previous_balance' => $oldQuantity,
                'new_balance' => $newQuantity,
                'notes' => $request->notes,
                'created_by' => $userId,
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            $balance->update(['quantity_on_hand' => $newQuantity]);
        });

        return redirect()->back()->with('success', 'Stock received successfully.');
    }

    public function storeIssue(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:stock_locations,id',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $tenantId = Auth::user()->tenant_id;
            $userId = Auth::id();
            $quantity = abs($request->quantity);

            $balance = StockBalance::firstOrCreate(
                ['product_id' => $request->product_id, 'location_id' => $request->location_id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            $oldQuantity = $balance->quantity_on_hand;
            $newQuantity = $oldQuantity - $quantity;

            $movementType = StockMovementType::where('name', 'issue')->first();

            StockMovement::create([
                'tenant_id' => $tenantId,
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
                'movement_type_id' => $movementType ? $movementType->id : null,
                'movement_type' => 'issue',
                'quantity' => -$quantity,
                'previous_balance' => $oldQuantity,
                'new_balance' => $newQuantity,
                'notes' => $request->notes,
                'created_by' => $userId,
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            $balance->update(['quantity_on_hand' => $newQuantity]);
        });

        return redirect()->back()->with('success', 'Stock issued successfully.');
    }

    public function storeDamage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:stock_locations,id',
            'quantity' => 'required|numeric|min:0.01',
            'damage_type' => 'nullable|string',
            'severity' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $tenantId = Auth::user()->tenant_id;
            $userId = Auth::id();
            $quantity = abs($request->quantity);

            $balance = StockBalance::firstOrCreate(
                ['product_id' => $request->product_id, 'location_id' => $request->location_id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            $oldQuantity = $balance->quantity_on_hand;
            $newQuantity = $oldQuantity - $quantity;

            // Log damage
            $damage = StockDamage::create([
                'tenant_id' => $tenantId,
                'damage_number' => 'DMG-' . strtoupper(uniqid()),
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
                'quantity' => $quantity,
                'damage_type' => $request->damage_type,
                'severity' => $request->severity,
                'report_notes' => $request->notes,
                'reported_by' => $userId,
                'reported_at' => now(),
                'status' => 'reported',
            ]);

            $movementType = StockMovementType::where('name', 'damage')->first();

            StockMovement::create([
                'tenant_id' => $tenantId,
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
                'movement_type_id' => $movementType ? $movementType->id : null,
                'movement_type' => 'damage',
                'quantity' => -$quantity,
                'previous_balance' => $oldQuantity,
                'new_balance' => $newQuantity,
                'reference_type' => StockDamage::class,
                'reference_id' => $damage->id,
                'notes' => $request->notes,
                'created_by' => $userId,
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            $balance->update([
                'quantity_on_hand' => $newQuantity,
                'quantity_damaged' => $balance->quantity_damaged + $quantity,
            ]);
        });

        return redirect()->back()->with('success', 'Stock damage reported successfully.');
    }

    public function storeCount(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:stock_locations,id',
            'counted_quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $tenantId = Auth::user()->tenant_id;
            $userId = Auth::id();

            $balance = StockBalance::firstOrCreate(
                ['product_id' => $request->product_id, 'location_id' => $request->location_id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            $expected = $balance->quantity_on_hand;
            $counted = $request->counted_quantity;
            $variance = $counted - $expected;
            $variancePercentage = $expected > 0 ? ($variance / $expected) * 100 : 0;

            $count = StockCount::create([
                'tenant_id' => $tenantId,
                'count_number' => 'CNT-' . strtoupper(uniqid()),
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
                'expected_quantity' => $expected,
                'counted_quantity' => $counted,
                'variance_percentage' => $variancePercentage,
                'counted_by' => $userId,
                'counted_at' => now(),
                'verified_by' => $userId,
                'verified_at' => now(),
                'notes' => $request->notes,
                'status' => 'completed',
            ]);

            if ($variance != 0) {
                $movementType = StockMovementType::where('name', 'count_adjustment')->first();

                StockMovement::create([
                    'tenant_id' => $tenantId,
                    'product_id' => $request->product_id,
                    'location_id' => $request->location_id,
                    'movement_type_id' => $movementType ? $movementType->id : null,
                    'movement_type' => 'count_adjustment',
                    'quantity' => $variance,
                    'previous_balance' => $expected,
                    'new_balance' => $counted,
                    'reference_type' => StockCount::class,
                    'reference_id' => $count->id,
                    'notes' => $request->notes,
                    'created_by' => $userId,
                    'approved_by' => $userId,
                    'approved_at' => now(),
                ]);

                $balance->update(['quantity_on_hand' => $counted, 'last_counted_at' => now()]);
            } else {
                $balance->update(['last_counted_at' => now()]);
            }
        });

        return redirect()->back()->with('success', 'Stock count recorded successfully.');
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:stock_locations,id',
            'quantity' => 'required|numeric|min:0.01',
            'return_reason' => 'nullable|string',
            'condition' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $tenantId = Auth::user()->tenant_id;
            $userId = Auth::id();
            $quantity = abs($request->quantity);

            $balance = StockBalance::firstOrCreate(
                ['product_id' => $request->product_id, 'location_id' => $request->location_id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
            );

            // Log return
            $returnRecord = StockReturn::create([
                'tenant_id' => $tenantId,
                'return_number' => 'RET-' . strtoupper(uniqid()),
                'product_id' => $request->product_id,
                'location_id' => $request->location_id,
                'quantity' => $quantity,
                'return_reason' => $request->return_reason,
                'condition' => $request->condition,
                'created_by' => $userId,
                'status' => 'pending', // Pending inspection/restocking
            ]);

            // Add to returned quantity (doesn't add to on_hand until restocked)
            $balance->update([
                'quantity_returned' => $balance->quantity_returned + $quantity,
            ]);
        });

        return redirect()->back()->with('success', 'Stock return recorded successfully.');
    }
    public function bomIndex()
    {
        $tenantId = Auth::user()->tenant_id;
        $featureService = app(\App\Services\ModuleFeatureService::class);

        if (!$featureService->isFeatureEnabled('inventory.bom')) {
            abort(403, 'Bill of Materials feature is not enabled.');
        }

        $boms = \App\Models\BillOfMaterial::with('product')->withCount('items')->where('tenant_id', $tenantId)->get();
        $products = Product::where('tenant_id', $tenantId)->where('is_active', true)->get();

        return view('product_service.stocks.bom_index', compact('boms', 'products'));
    }

    public function storeBom(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.0001',
            'components' => 'required|array|min:1',
            'components.*.id' => 'required|exists:products,id',
            'components.*.qty' => 'required|numeric|min:0.0001',
        ]);

        $tenantId = Auth::user()->tenant_id;

        DB::transaction(function() use ($request, $tenantId) {
            $bom = \App\Models\BillOfMaterial::create([
                'tenant_id' => $tenantId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);

            foreach ($request->components as $comp) {
                \App\Models\BomItem::create([
                    'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                    'bill_of_material_id' => $bom->id,
                    'component_id' => $comp['id'],
                    'quantity' => $comp['qty'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'BOM created successfully.');
    }

    public function productionOrders()
    {
        $tenantId = Auth::user()->tenant_id;
        $featureService = app(\App\Services\ModuleFeatureService::class);

        if (!$featureService->isFeatureEnabled('inventory.production_orders')) {
            abort(403, 'Production Orders feature is not enabled.');
        }

        $boms = \App\Models\BillOfMaterial::with('product')->where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.production_orders', compact('boms'));
    }

    public function storeProductionOrder(Request $request)
    {
        $request->validate([
            'bill_of_material_id' => 'required|exists:bill_of_materials,id',
            'quantity' => 'required|numeric|min:0.0001',
        ]);

        $bom = \App\Models\BillOfMaterial::with('product')->findOrFail($request->bill_of_material_id);
        return redirect()->back()->with('success', 'Production run for ' . $bom->product->name . ' started.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $balance = StockBalance::with(['product', 'location', 'bin'])
            ->whereHas('product', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })->findOrFail($id);

        $movements = StockMovement::with(['creator', 'movementType'])
            ->where('tenant_id', $tenantId)
            ->where('product_id', $balance->product_id)
            ->where('location_id', $balance->location_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('product_service.stocks.show', compact('balance', 'movements'));
    }

    public function createReceive()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)->get();
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.receive', compact('products', 'locations'));
    }

    public function createIssue()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)->get();
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.issue', compact('products', 'locations'));
    }

    public function createTransfer()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)->get();
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.transfer', compact('products', 'locations'));
    }

    public function createAdjustment()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)->get();
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        $reasons = StockAdjustmentReason::where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.adjust', compact('products', 'locations', 'reasons'));
    }

    public function createDamage()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)->get();
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.damage', compact('products', 'locations'));
    }

    public function createCount()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)->get();
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.count', compact('products', 'locations'));
    }

    public function createReturn()
    {
        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('tenant_id', $tenantId)->get();
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.return', compact('products', 'locations'));
    }

    public function bins()
    {
        $tenantId = Auth::user()->tenant_id;
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        $locationIds = $locations->pluck('id');
        $bins = StockBin::with('location')->whereIn('location_id', $locationIds)->get();

        return view('product_service.stocks.bins', compact('bins', 'locations'));
    }

    public function storeBin(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:stock_locations,id',
            'bin_code' => 'required|string|max:100',
            'bin_type' => 'nullable|string|max:50',
        ]);

        StockBin::create([
            'location_id' => $request->location_id,
            'bin_code' => $request->bin_code,
            'bin_type' => $request->bin_type,
            'is_active' => true,
        ]);

        return redirect()->route('product_service.stocks.bins.index')->with('success', 'Bin created successfully.');
    }

    // ────────────────────────────────────────────────
    //  Locations Management
    // ────────────────────────────────────────────────

    public function locations()
    {
        $tenantId = Auth::user()->tenant_id;
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        return view('product_service.stocks.locations', compact('locations'));
    }

    public function storeLocation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_type' => 'required|string|max:255',
            'address' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $isDefault = $request->boolean('is_default');
        $isActive = $request->boolean('is_active', true);

        // If setting as default, unset others
        if ($isDefault) {
            StockLocation::where('tenant_id', $tenantId)->update(['is_default' => false]);
        }

        $location = StockLocation::create([
            'tenant_id' => $tenantId,
            'name' => $request->name,
            'location_type' => $request->location_type,
            'address' => $request->address,
            'is_default' => $isDefault,
            'is_active' => $isActive,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => "Location '{$location->name}' created successfully.",
                'location' => ['id' => $location->id, 'name' => $location->name],
            ], 201);
        }

        if ($request->boolean('from_product')) {
            $draft = session()->get('product_create_draft', []);
            $draft['stock_location_id'] = $location->id;
            session()->put('product_create_draft', $draft);
            return redirect()->route('product_service.products.create')
                ->with('success', "Location '{$location->name}' created! Your product draft has been restored.");
        }

        return redirect()->route('product_service.stocks.locations.index')->with('success', 'Location created successfully.');
    }

    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_type' => 'required|string|max:255',
            'address' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $location = StockLocation::where('tenant_id', $tenantId)->findOrFail($id);
        $isDefault = $request->boolean('is_default');
        $isActive = $request->boolean('is_active');

        if ($isDefault && !$location->is_default) {
            StockLocation::where('tenant_id', $tenantId)->update(['is_default' => false]);
        }

        $location->update([
            'name' => $request->name,
            'location_type' => $request->location_type,
            'address' => $request->address,
            'is_default' => $isDefault,
            'is_active' => $isActive,
        ]);

        return redirect()->route('product_service.stocks.locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroyLocation($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $location = StockLocation::where('tenant_id', $tenantId)->findOrFail($id);

        // Prevent deletion if in use
        if ($location->stockBalances()->exists() || $location->bins()->exists() || $location->posDevices()->exists()) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete location because it is currently in use (has stock, bins, or POS devices).']);
        }

        $location->delete();

        return redirect()->route('product_service.stocks.locations.index')->with('success', 'Location deleted successfully.');
    }

    private function buildStockDetailsByProduct(string $tenantId, $products, $balances): array
    {
        $stockedProductIds = $balances->pluck('product_id')->unique()->values();

        if ($stockedProductIds->isEmpty()) {
            return [];
        }

        $movements = StockMovement::with(['location', 'movementType', 'creator'])
            ->where('tenant_id', $tenantId)
            ->whereIn('product_id', $stockedProductIds)
            ->latest()
            ->get()
            ->groupBy('product_id');

        $adjustments = StockAdjustment::with(['location', 'reason', 'approver'])
            ->where('tenant_id', $tenantId)
            ->whereIn('product_id', $stockedProductIds)
            ->latest()
            ->get()
            ->groupBy('product_id');

        $transfers = StockTransfer::with(['fromLocation', 'toLocation'])
            ->where('tenant_id', $tenantId)
            ->whereIn('product_id', $stockedProductIds)
            ->latest()
            ->get()
            ->groupBy('product_id');

        $damages = StockDamage::with(['location', 'reporter'])
            ->where('tenant_id', $tenantId)
            ->whereIn('product_id', $stockedProductIds)
            ->latest()
            ->get()
            ->groupBy('product_id');

        $counts = StockCount::with(['location', 'schedule', 'counter'])
            ->where('tenant_id', $tenantId)
            ->whereIn('product_id', $stockedProductIds)
            ->latest()
            ->get()
            ->groupBy('product_id');

        $returns = StockReturn::with(['location', 'creator'])
            ->where('tenant_id', $tenantId)
            ->whereIn('product_id', $stockedProductIds)
            ->latest()
            ->get()
            ->groupBy('product_id');

        $details = [];

        foreach ($stockedProductIds as $productId) {
            $product = $products->firstWhere('id', $productId);
            if (! $product) {
                continue;
            }

            $productBalances = $balances->where('product_id', $productId)->values();
            $locationIds = $productBalances->pluck('location_id')->filter()->unique()->values();

            $productLocations = StockLocation::where('tenant_id', $tenantId)
                ->whereIn('id', $locationIds)
                ->get();

            $productBins = StockBin::with('location')
                ->whereIn('location_id', $locationIds)
                ->get();

            $countSchedules = StockCountSchedule::with('location')
                ->where('tenant_id', $tenantId)
                ->whereIn('location_id', $locationIds)
                ->get();

            $images = collect($product->images ?? [])
                ->values()
                ->map(fn ($_, $index) => $product->imageUrl($index))
                ->filter()
                ->values()
                ->all();

            $details[$productId] = [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'description' => $product->description,
                    'brand' => $product->brand,
                    'category' => $product->category?->name,
                    'unit_price' => $product->unit_price,
                    'cost_price' => $product->cost_price,
                    'unit_type' => $product->unit_type,
                    'weight_kg' => $product->weight_kg,
                    'tax_rate' => $product->tax_rate,
                    'is_active' => (bool) $product->is_active,
                    'min_stock_level' => $product->min_stock_level,
                    'max_stock_level' => $product->max_stock_level,
                    'reorder_point' => $product->reorder_point,
                    'reorder_quantity' => $product->reorder_quantity,
                    'images' => $images,
                ],
                'balances' => $productBalances->map(fn (StockBalance $balance) => [
                    'quantity_on_hand' => $balance->quantity_on_hand,
                    'quantity_reserved' => $balance->quantity_reserved,
                    'quantity_in_transit' => $balance->quantity_in_transit,
                    'quantity_damaged' => $balance->quantity_damaged,
                    'quantity_returned' => $balance->quantity_returned,
                    'quantity_available' => $balance->quantity_available,
                    'reorder_status' => $balance->reorder_status,
                    'last_counted_at' => $balance->last_counted_at?->format('M d, Y h:i A'),
                    'location' => $balance->location?->name,
                    'location_type' => $balance->location?->location_type,
                    'bin' => $balance->bin?->bin_code,
                ])->values()->all(),
                'movements' => ($movements->get($productId) ?? collect())->map(fn (StockMovement $movement) => [
                    'date' => $movement->created_at?->format('M d, Y h:i A'),
                    'type' => $movement->movementType?->name ?? $movement->movement_type,
                    'location' => $movement->location?->name,
                    'quantity' => $movement->quantity,
                    'previous_balance' => $movement->previous_balance,
                    'new_balance' => $movement->new_balance,
                    'notes' => $movement->notes,
                    'created_by' => $movement->creator?->name,
                ])->values()->all(),
                'adjustments' => ($adjustments->get($productId) ?? collect())->map(fn (StockAdjustment $adjustment) => [
                    'number' => $adjustment->adjustment_number,
                    'date' => $adjustment->created_at?->format('M d, Y h:i A'),
                    'location' => $adjustment->location?->name,
                    'reason' => $adjustment->reason?->reason_name,
                    'type' => $adjustment->adjustment_type,
                    'old_quantity' => $adjustment->old_quantity,
                    'new_quantity' => $adjustment->new_quantity,
                    'change' => $adjustment->quantity_change,
                    'status' => $adjustment->status,
                    'approved_by' => $adjustment->approver?->name,
                ])->values()->all(),
                'transfers' => ($transfers->get($productId) ?? collect())->map(fn (StockTransfer $transfer) => [
                    'number' => $transfer->transfer_number,
                    'date' => $transfer->created_at?->format('M d, Y h:i A'),
                    'from' => $transfer->fromLocation?->name,
                    'to' => $transfer->toLocation?->name,
                    'quantity' => $transfer->quantity,
                    'status' => $transfer->status,
                    'notes' => $transfer->notes,
                ])->values()->all(),
                'damages' => ($damages->get($productId) ?? collect())->map(fn (StockDamage $damage) => [
                    'number' => $damage->damage_number,
                    'date' => $damage->reported_at?->format('M d, Y h:i A') ?? $damage->created_at?->format('M d, Y h:i A'),
                    'location' => $damage->location?->name,
                    'quantity' => $damage->quantity,
                    'type' => $damage->damage_type,
                    'severity' => $damage->severity,
                    'status' => $damage->status,
                    'reported_by' => $damage->reporter?->name,
                ])->values()->all(),
                'counts' => ($counts->get($productId) ?? collect())->map(fn (StockCount $count) => [
                    'number' => $count->count_number,
                    'date' => $count->counted_at?->format('M d, Y h:i A') ?? $count->created_at?->format('M d, Y h:i A'),
                    'location' => $count->location?->name,
                    'schedule' => $count->schedule?->name,
                    'expected' => $count->expected_quantity,
                    'counted' => $count->counted_quantity,
                    'variance' => $count->variance,
                    'status' => $count->status,
                    'counted_by' => $count->counter?->name,
                ])->values()->all(),
                'returns' => ($returns->get($productId) ?? collect())->map(fn (StockReturn $return) => [
                    'number' => $return->return_number,
                    'date' => $return->created_at?->format('M d, Y h:i A'),
                    'location' => $return->location?->name,
                    'quantity' => $return->quantity,
                    'reason' => $return->return_reason,
                    'condition' => $return->condition,
                    'status' => $return->status,
                    'created_by' => $return->creator?->name,
                ])->values()->all(),
                'locations' => $productLocations->map(fn (StockLocation $location) => [
                    'name' => $location->name,
                    'type' => $location->location_type,
                    'address' => $location->address,
                    'is_default' => (bool) $location->is_default,
                    'is_active' => (bool) $location->is_active,
                ])->values()->all(),
                'bins' => $productBins->map(fn (StockBin $bin) => [
                    'code' => $bin->bin_code,
                    'type' => $bin->bin_type,
                    'location' => $bin->location?->name,
                    'is_active' => (bool) $bin->is_active,
                ])->values()->all(),
                'count_schedules' => $countSchedules->map(fn (StockCountSchedule $schedule) => [
                    'name' => $schedule->name,
                    'location' => $schedule->location?->name,
                    'count_type' => $schedule->count_type,
                    'frequency' => $schedule->frequency,
                    'next_count_date' => $schedule->next_count_date?->format('M d, Y'),
                    'is_active' => (bool) $schedule->is_active,
                ])->values()->all(),
            ];
        }

        return $details;
    }
}
