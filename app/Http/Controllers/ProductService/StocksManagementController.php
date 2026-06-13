<?php

namespace App\Http\Controllers\ProductService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductCategory;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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


class StocksManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(CheckPermission::class . ':product_service,view', only: [
                'index', 'movements', 'show', 'createReceive', 'createIssue', 
                'createTransfer', 'createAdjustment', 'createDamage', 'createCount', 
                'createReturn', 'bins', 'locations'
            ]),
            new Middleware(CheckPermission::class . ':product_service,update', only: [
                'storeAdjustment', 'storeTransfer', 'storeReceive', 'storeIssue', 
                'storeDamage', 'storeCount', 'storeReturn', 'storeBin',
                'storeLocation', 'updateLocation', 'destroyLocation'
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
            
        $locations = StockLocation::where('tenant_id', $tenantId)->get();
        $products = Product::where('tenant_id', $tenantId)->get();
        $reasons = StockAdjustmentReason::where('tenant_id', $tenantId)->get();

        return view('product_service.stocks.index', compact('balances', 'locations', 'products', 'reasons'));
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
        $bins = StockBin::with('location')->where('tenant_id', $tenantId)->get();
        
        return view('product_service.stocks.bins', compact('bins', 'locations'));
    }

    public function storeBin(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:stock_locations,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'capacity' => 'nullable|numeric|min:0',
        ]);

        StockBin::create([
            'tenant_id' => Auth::user()->tenant_id,
            'location_id' => $request->location_id,
            'name' => $request->name,
            'barcode' => $request->barcode,
            'capacity' => $request->capacity,
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
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $tenantId = Auth::user()->tenant_id;

        // If setting as default, unset others
        if ($request->is_default) {
            StockLocation::where('tenant_id', $tenantId)->update(['is_default' => false]);
        }

        StockLocation::create([
            'tenant_id' => $tenantId,
            'name' => $request->name,
            'location_type' => $request->location_type,
            'address' => $request->address,
            'is_default' => $request->is_default ?? false,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('product_service.stocks.locations.index')->with('success', 'Location created successfully.');
    }

    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_type' => 'required|string|max:255',
            'address' => 'nullable|string',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $location = StockLocation::where('tenant_id', $tenantId)->findOrFail($id);

        if ($request->is_default && !$location->is_default) {
            StockLocation::where('tenant_id', $tenantId)->update(['is_default' => false]);
        }

        $location->update([
            'name' => $request->name,
            'location_type' => $request->location_type,
            'address' => $request->address,
            'is_default' => $request->is_default ?? false,
            'is_active' => $request->is_active ?? true,
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
}
