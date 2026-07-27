<?php

namespace App\Http\Controllers\ProductService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckPermission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\PosDevice;
use App\Models\PosSession;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Customer;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\StockMovementType;
use App\Models\PosTable;
use App\Services\Pos\PosConnectionService;
use App\Services\ModuleFeatureService;

class PosController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly ModuleFeatureService $featureService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(CheckPermission::class . ':product_service,view', only: ['index', 'orders', 'sessions', 'showOrder', 'deviceStatus', 'dailySales', 'devices', 'drawerAccess', 'tables', 'kitchen']),
            new Middleware(CheckPermission::class . ':product_service,update', only: [
                'openSession', 'closeSession', 'checkout', 'connectDevice',
                'testPrint', 'openDrawer', 'printOrderReceipt', 'storeDevice'
            ]),
        ];
    }

    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();

        // Check if cashier has an active session
        $activeSession = PosSession::where('tenant_id', $tenantId)
            ->where('cashier_id', $userId)
            ->whereNull('ended_at')
            ->first();

        // Data for the POS interface
        $devices = PosDevice::where('tenant_id', $tenantId)->get();
        $categories = ProductCategory::where('tenant_id', $tenantId)->get();
        $products = Product::where('tenant_id', $tenantId)->where('is_active', true)->get();
        $customers = Customer::where('tenant_id', $tenantId)->get();

        $tables = [];
        if (in_array('pos.tables', $this->featureService->getEnabledFeatures())) {
            $tables = PosTable::where('tenant_id', $tenantId)->get();
        }

        return view('product_service.pos.index', compact('activeSession', 'devices', 'categories', 'products', 'customers', 'tables'));
    }

    public function openSession(Request $request, PosConnectionService $posService)
    {
        $request->validate([
            'pos_device_id' => 'nullable|exists:pos_devices,id',
            'opening_balance' => 'required|numeric|min:0',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();

        // Ensure no active session exists for this user
        $activeSession = PosSession::where('tenant_id', $tenantId)
            ->where('cashier_id', $userId)
            ->whereNull('ended_at')
            ->first();

        if ($activeSession) {
            return redirect()->back()->withErrors(['session' => 'You already have an active POS session.']);
        }

        // Auto-connect to device if selected
        $deviceConnected = false;
        if ($request->pos_device_id) {
            $device = PosDevice::where('tenant_id', $tenantId)->findOrFail($request->pos_device_id);
            $deviceConnected = $posService->autoConnect($device);
        }

        $session = PosSession::create([
            'tenant_id' => $tenantId,
            'pos_device_id' => $request->pos_device_id,
            'cashier_id' => $userId,
            'started_at' => now(),
            'opening_balance' => $request->opening_balance,
            'cash_sales' => 0,
            'card_sales' => 0,
            'expected_cash' => $request->opening_balance,
            'status' => 'open',
        ]);

        // Auto-open cash drawer if device connected
        if ($deviceConnected && $request->pos_device_id) {
            try {
                $device = PosDevice::find($request->pos_device_id);
                $posService->openCashDrawer($device);
            } catch (\Exception $e) {
                Log::warning("Could not open drawer on session start: " . $e->getMessage());
            }
        }

        $message = 'POS Session opened.';
        if ($request->pos_device_id) {
            $message .= $deviceConnected ? ' Device connected.' : ' Warning: Device could not be reached.';
        } else {
            $message .= ' Running in manual mode (No Hardware).';
        }

        return redirect()->route('product_service.pos.index')->with('success', $message);
    }

    public function closeSession(Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();

        $session = PosSession::where('tenant_id', $tenantId)
            ->where('cashier_id', $userId)
            ->whereNull('ended_at')
            ->firstOrFail();

        $variance = $request->actual_cash - $session->expected_cash;

        $session->update([
            'ended_at' => now(),
            'actual_cash' => $request->actual_cash,
            'closing_balance' => $request->actual_cash,
            'variance' => $variance,
            'status' => 'closed',
        ]);

        return redirect()->route('product_service.pos.index')->with('success', 'POS Session closed successfully.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|string|in:cash,card',
            'paid_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();

        // Get active session
        $session = PosSession::with('device')->where('tenant_id', $tenantId)
            ->where('cashier_id', $userId)
            ->whereNull('ended_at')
            ->firstOrFail();

        DB::transaction(function () use ($request, $tenantId, $userId, $session) {
            $subtotal = 0;
            $totalDiscount = 0;

            // Calculate totals
            foreach ($request->items as $item) {
                $subtotal += ($item['quantity'] * $item['unit_price']);
                $totalDiscount += ($item['discount_amount'] ?? 0);
            }

            $taxAmount = 0; // Tax calculation logic would go here if needed
            $totalAmount = $subtotal - $totalDiscount + $taxAmount;
            $changeAmount = max(0, $request->paid_amount - $totalAmount);

            // Create Order
            $orderStatus = in_array('pos.tables', $this->featureService->getEnabledFeatures()) ? 'pending' : 'completed';

            $order = PosOrder::create([
                'tenant_id' => $tenantId,
                'pos_session_id' => $session->id,
                'order_number' => 'ORD-' . strtoupper(substr(uniqid(), -6)),
                'customer_id' => $request->customer_id,
                'order_type' => 'sale',
                'status' => $orderStatus,
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $changeAmount,
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'completed_at' => now(),
            ]);

            $movementType = StockMovementType::firstOrCreate(
                ['name' => 'sale'],
                ['movement_direction' => 'out', 'affects_balance' => true]
            );

            // Process Items & Deduct Stock
            foreach ($request->items as $item) {
                $itemTotal = ($item['quantity'] * $item['unit_price']) - ($item['discount_amount'] ?? 0);

                PosOrderItem::create([
                    'pos_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'total_price' => $itemTotal,
                ]);

                // Update Stock (assuming the device belongs to a specific location)
                $locationId = $session->device->location_id ?? null;

                if ($locationId) {
                    $balance = StockBalance::firstOrCreate(
                        ['product_id' => $item['product_id'], 'location_id' => $locationId],
                        ['quantity_on_hand' => 0, 'quantity_reserved' => 0, 'quantity_in_transit' => 0, 'quantity_damaged' => 0, 'quantity_returned' => 0]
                    );

                    $oldQuantity = $balance->quantity_on_hand;
                    $newQuantity = $oldQuantity - $item['quantity'];

                    StockMovement::create([
                        'tenant_id' => $tenantId,
                        'product_id' => $item['product_id'],
                        'location_id' => $locationId,
                        'movement_type_id' => $movementType->id,
                        'movement_type' => 'sale',
                        'quantity' => -$item['quantity'],
                        'previous_balance' => $oldQuantity,
                        'new_balance' => $newQuantity,
                        'reference_type' => PosOrder::class,
                        'reference_id' => $order->id,
                        'created_by' => $userId,
                        'approved_by' => $userId,
                        'approved_at' => now(),
                    ]);

                    $balance->update(['quantity_on_hand' => $newQuantity]);
                }
            }

            // Update Session Totals
            if ($request->payment_method === 'cash') {
                $session->cash_sales += $totalAmount;
                $session->expected_cash += $totalAmount;
            } else {
                $session->card_sales += $totalAmount;
            }
            $session->save();
        });

        return redirect()->back()->with('success', 'Order completed successfully.');
    }

    public function orders()
    {
        $tenantId = Auth::user()->tenant_id;
        $orders = PosOrder::with(['customer', 'session.cashier'])
            ->where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('product_service.pos.orders', compact('orders'));
    }

    public function showOrder($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $order = PosOrder::with(['items.product', 'customer', 'session.cashier'])
            ->where('tenant_id', $tenantId)
            ->findOrFail($id);

        return view('product_service.pos.show_order', compact('order'));
    }

    public function sessions()
    {
        $tenantId = Auth::user()->tenant_id;
        $sessions = PosSession::with(['device', 'cashier'])
            ->where('tenant_id', $tenantId)
            ->orderBy('started_at', 'desc')
            ->paginate(20);

        return view('product_service.pos.sessions', compact('sessions'));
    }

    public function dailySales()
    {
        $tenantId = Auth::user()->tenant_id;
        $sales = PosOrder::where('tenant_id', $tenantId)
            ->whereDate('completed_at', now()->toDateString())
            ->get();

        $stats = [
            'total_sales' => $sales->sum('total_amount'),
            'order_count' => $sales->count(),
            'cash_total' => $sales->where('payment_method', 'cash')->sum('total_amount'),
            'card_total' => $sales->where('payment_method', 'card')->sum('total_amount'),
        ];

        return view('product_service.pos.daily_sales', compact('stats', 'sales'));
    }

    public function devices()
    {
        $tenantId = Auth::user()->tenant_id;
        $devices = PosDevice::with('location')->where('tenant_id', $tenantId)->get();
        $locations = \App\Models\StockLocation::where('tenant_id', $tenantId)->where('is_active', true)->get();

        return view('product_service.pos.devices', compact('devices', 'locations'));
    }

    public function storeDevice(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:100',
            'location_id' => 'nullable|exists:stock_locations,id',
            'connection_type' => 'required|string|in:network,usb',
            'ip_address' => 'required_if:connection_type,network|nullable|ip',
            'port' => 'required_if:connection_type,network|nullable|integer',
            'serial_number' => 'nullable|string|max:100',
        ]);

        $tenantId = Auth::user()->tenant_id;

        PosDevice::create([
            'tenant_id' => $tenantId,
            'device_name' => $request->device_name,
            'location_id' => $request->location_id,
            'connection_type' => $request->connection_type,
            'ip_address' => $request->ip_address,
            'port' => $request->port ?? 9100,
            'serial_number' => $request->serial_number,
            'status' => 'offline',
        ]);

        return redirect()->back()->with('success', 'Hardware device added successfully.');
    }

    public function drawerAccess()
    {
        $tenantId = Auth::user()->tenant_id;
        $logs = \App\Models\PosDrawerAccess::with(['user', 'device'])
            ->where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('product_service.pos.drawer_access', compact('logs'));
    }

    public function tables()
    {
        $tenantId = Auth::user()->tenant_id;
        if (!$this->featureService->isFeatureEnabled('pos.tables')) {
            abort(403, 'Restaurant Tables feature is not enabled for your workspace.');
        }

        $tables = PosTable::where('tenant_id', $tenantId)->get();
        return view('product_service.pos.tables', compact('tables'));
    }

    public function storeTable(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
        ]);

        PosTable::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'status' => 'available',
        ]);

        return redirect()->back()->with('success', 'Table added successfully.');
    }

    public function kitchen()
    {
        if (!$this->featureService->isFeatureEnabled('pos.kitchen_display')) {
            abort(403, 'Kitchen Display feature is not enabled for your workspace.');
        }

        $tenantId = Auth::user()->tenant_id;
        // In a real app, you'd fetch pending orders with 'food' items
        $orders = PosOrder::with('items.product')->where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('product_service.pos.kitchen', compact('orders'));
    }

    public function completeKitchenOrder($id)
    {
        $order = PosOrder::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $order->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Order marked as ready.');
    }

    public function connectDevice(Request $request, PosConnectionService $posService)
    {
        $request->validate([
            'pos_device_id' => 'required|exists:pos_devices,id',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $device = PosDevice::where('tenant_id', $tenantId)->findOrFail($request->pos_device_id);

        $connected = $posService->autoConnect($device);

        if ($connected) {
            return redirect()->back()->with('success', 'Successfully connected to ' . $device->device_name);
        } else {
            return redirect()->back()->withErrors(['device' => 'Failed to connect to ' . $device->device_name]);
        }
    }

    public function testPrint(Request $request, PosConnectionService $posService)
    {
        $request->validate([
            'pos_device_id' => 'required|exists:pos_devices,id',
        ]);

        $device = PosDevice::where('tenant_id', Auth::user()->tenant_id)->findOrFail($request->pos_device_id);

        try {
            $posService->printReceipt($device, [
                'type' => 'test',
                'content' => 'Test Receipt from flowexa',
                'date' => now()->toDateTimeString()
            ]);
            return redirect()->back()->with('success', 'Test print sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['device' => $e->getMessage()]);
        }
    }

    public function openDrawer(Request $request, PosConnectionService $posService)
    {
        $request->validate([
            'pos_device_id' => 'required|exists:pos_devices,id',
            'reason' => 'nullable|string|max:255',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();
        $device = PosDevice::where('tenant_id', $tenantId)->findOrFail($request->pos_device_id);

        try {
            $posService->openCashDrawer($device);

            // Log the access
            $activeSession = PosSession::where('tenant_id', $tenantId)
                ->where('cashier_id', $userId)
                ->whereNull('ended_at')
                ->first();

            \App\Models\PosDrawerAccess::create([
                'tenant_id' => $tenantId,
                'pos_session_id' => $activeSession?->id,
                'pos_device_id' => $device->id,
                'user_id' => $userId,
                'reason' => $request->reason ?? 'Manual open',
            ]);

            return redirect()->back()->with('success', 'Cash drawer opened.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['device' => $e->getMessage()]);
        }
    }

    public function printOrderReceipt($orderId, PosConnectionService $posService, Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $order = PosOrder::with(['items.product', 'customer', 'session.cashier', 'session.device'])
            ->where('tenant_id', $tenantId)
            ->findOrFail($orderId);

        $device = $order->session->device ?? null;

        // Force browser print if requested or no device
        if ($request->has('browser') || !$device) {
            return view('product_service.pos.receipt_print', compact('order'));
        }

        try {
            $posService->printOrderReceipt($device, $order);
            return redirect()->back()->with('success', 'Receipt sent to hardware printer.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['device' => 'Hardware print failed: ' . $e->getMessage() . '. Falling back to browser print.'])->with('fallback_print_url', route('product_service.pos.device.print-receipt', ['orderId' => $orderId, 'browser' => 1]));
        }
    }

    public function deviceStatus(Request $request, PosConnectionService $posService)
    {
        $request->validate([
            'pos_device_id' => 'required|exists:pos_devices,id',
        ]);

        $device = PosDevice::where('tenant_id', Auth::user()->tenant_id)->findOrFail($request->pos_device_id);
        $status = $posService->checkDeviceStatus($device);

        return redirect()->back()->with('device_status', $status);
    }
}
