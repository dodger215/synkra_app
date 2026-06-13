<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\AcceptInviteController;
use App\Http\Controllers\InviteController;

// ProductService Controllers
use App\Http\Controllers\ProductService\CategoriesController;
use App\Http\Controllers\ProductService\InventoryController;
use App\Http\Controllers\ProductService\StocksManagementController;
use App\Http\Controllers\ProductService\PosController;
use App\Http\Controllers\ProductService\ExportController;
use App\Http\Controllers\ProductService\ImportController;

// SupplyChain Controllers
use App\Http\Controllers\SupplyChain\SupplierController;
use App\Http\Controllers\SupplyChain\PurchasingController;
use App\Http\Controllers\SupplyChain\ReceivingController;
use App\Http\Controllers\SupplyChain\ForecastController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/invite', [InviteController::class, 'store'])->middleware(['auth', 'permission:settings,invite_users']);
Route::get('/invites', [InviteController::class, 'index'])->middleware(['auth', 'permission:settings,manage_users']);
Route::post('/invite/{id}/resend', [InviteController::class, 'resend'])->middleware(['auth', 'permission:settings,invite_users'])->name('invite.resend');
Route::post('/invite/{id}/revoke', [InviteController::class, 'revoke'])->middleware(['auth', 'permission:settings,manage_users'])->name('invite.revoke');
Route::get('/accept-invite/{token}', [AcceptInviteController::class, 'show'])->name('invite.accept');
Route::post('/accept-invite/{token}', [AcceptInviteController::class, 'store']);

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Settings Routes
Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Settings\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\Settings\ProfileController::class, 'update'])->name('profile.update');

    // Workspace / Tenant Settings
    Route::middleware('permission:settings,edit_company_settings')->group(function () {
        Route::get('/workspace', [\App\Http\Controllers\Settings\TenantController::class, 'edit'])->name('workspace.edit');
        Route::post('/workspace', [\App\Http\Controllers\Settings\TenantController::class, 'update'])->name('workspace.update');
    });

    // Services (Owner Only)
    Route::middleware('permission:settings,manage_tenants')->group(function () {
        Route::get('/services', [\App\Http\Controllers\Settings\TenantServiceController::class, 'index'])->name('services.index');
        Route::post('/services/{id}', [\App\Http\Controllers\Settings\TenantServiceController::class, 'update'])->name('services.update');
    });

    // Contacts
    Route::middleware('permission:settings,edit_company_settings')->group(function () {
        Route::get('/contacts', [\App\Http\Controllers\Settings\TenantContactController::class, 'index'])->name('contacts.index');
        Route::post('/contacts', [\App\Http\Controllers\Settings\TenantContactController::class, 'store'])->name('contacts.store');
        Route::post('/contacts/{id}', [\App\Http\Controllers\Settings\TenantContactController::class, 'update'])->name('contacts.update');
        Route::delete('/contacts/{id}', [\App\Http\Controllers\Settings\TenantContactController::class, 'destroy'])->name('contacts.destroy');
    });

    // Subaccounts (Billing/Payments)
    Route::middleware('permission:settings,manage_billing')->group(function () {
        Route::get('/subaccounts', [\App\Http\Controllers\Settings\TenantSubaccountController::class, 'index'])->name('subaccounts.index');
        Route::post('/subaccounts', [\App\Http\Controllers\Settings\TenantSubaccountController::class, 'store'])->name('subaccounts.store');
        Route::post('/subaccounts/{id}', [\App\Http\Controllers\Settings\TenantSubaccountController::class, 'update'])->name('subaccounts.update');
        Route::delete('/subaccounts/{id}', [\App\Http\Controllers\Settings\TenantSubaccountController::class, 'destroy'])->name('subaccounts.destroy');
    });
});

// Product Service Routes
Route::middleware(['auth'])->prefix('product-service')->name('product_service.')->group(function () {
    // Categories
    Route::resource('categories', CategoriesController::class);

    // Products (InventoryController handles CRUD for products)
    Route::resource('products', InventoryController::class);

    // Stocks Management
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StocksManagementController::class, 'index'])->name('index');
        Route::get('/movements', [StocksManagementController::class, 'movements'])->name('movements');
        
        Route::get('/receive/create', [StocksManagementController::class, 'createReceive'])->name('receive.create');
        Route::post('/receive', [StocksManagementController::class, 'storeReceive'])->name('receive.store');
        
        Route::get('/issue/create', [StocksManagementController::class, 'createIssue'])->name('issue.create');
        Route::post('/issue', [StocksManagementController::class, 'storeIssue'])->name('issue.store');
        
        Route::get('/transfer/create', [StocksManagementController::class, 'createTransfer'])->name('transfer.create');
        Route::post('/transfer', [StocksManagementController::class, 'storeTransfer'])->name('transfer.store');
        
        Route::get('/adjustment/create', [StocksManagementController::class, 'createAdjustment'])->name('adjustment.create');
        Route::post('/adjustment', [StocksManagementController::class, 'storeAdjustment'])->name('adjustment.store');
        
        Route::get('/damage/create', [StocksManagementController::class, 'createDamage'])->name('damage.create');
        Route::post('/damage', [StocksManagementController::class, 'storeDamage'])->name('damage.store');
        
        Route::get('/count/create', [StocksManagementController::class, 'createCount'])->name('count.create');
        Route::post('/count', [StocksManagementController::class, 'storeCount'])->name('count.store');
        
        Route::get('/return/create', [StocksManagementController::class, 'createReturn'])->name('return.create');
        Route::post('/return', [StocksManagementController::class, 'storeReturn'])->name('return.store');

        Route::get('/locations', [StocksManagementController::class, 'locations'])->name('locations.index');
        Route::post('/locations', [StocksManagementController::class, 'storeLocation'])->name('locations.store');
        Route::put('/locations/{id}', [StocksManagementController::class, 'updateLocation'])->name('locations.update');
        Route::delete('/locations/{id}', [StocksManagementController::class, 'destroyLocation'])->name('locations.destroy');

        Route::get('/bins', [StocksManagementController::class, 'bins'])->name('bins.index');
        Route::post('/bins', [StocksManagementController::class, 'storeBin'])->name('bins.store');
        
        Route::get('/{id}', [StocksManagementController::class, 'show'])->name('show');
    });

    // POS
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/session/open', [PosController::class, 'openSession'])->name('session.open');
        Route::post('/session/close', [PosController::class, 'closeSession'])->name('session.close');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
        
        Route::get('/orders', [PosController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [PosController::class, 'showOrder'])->name('order.show');
        Route::get('/sessions', [PosController::class, 'sessions'])->name('sessions');
        
        // POS Device Integrations
        Route::post('/device/connect', [PosController::class, 'connectDevice'])->name('device.connect');
        Route::post('/device/test-print', [PosController::class, 'testPrint'])->name('device.test-print');
        Route::post('/device/open-drawer', [PosController::class, 'openDrawer'])->name('device.open-drawer');
        Route::post('/device/print-receipt/{orderId}', [PosController::class, 'printOrderReceipt'])->name('device.print-receipt');
        Route::post('/device/status', [PosController::class, 'deviceStatus'])->name('device.status');
    });

    // Exports
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/products', [ExportController::class, 'products'])->name('products');
        Route::get('/stock-balances', [ExportController::class, 'stockBalances'])->name('stock_balances');
        Route::get('/stock-movements', [ExportController::class, 'stockMovements'])->name('stock_movements');
        Route::get('/stock-adjustments', [ExportController::class, 'stockAdjustments'])->name('stock_adjustments');
        Route::get('/stock-transfers', [ExportController::class, 'stockTransfers'])->name('stock_transfers');
        Route::get('/stock-damages', [ExportController::class, 'stockDamages'])->name('stock_damages');
        Route::get('/stock-counts', [ExportController::class, 'stockCounts'])->name('stock_counts');
        Route::get('/stock-returns', [ExportController::class, 'stockReturns'])->name('stock_returns');
        Route::get('/stock-locations', [ExportController::class, 'stockLocations'])->name('stock_locations');
        Route::get('/stock-bins', [ExportController::class, 'stockBins'])->name('stock_bins');
        Route::get('/pos-orders', [ExportController::class, 'posOrders'])->name('pos_orders');
        Route::get('/pos-sessions', [ExportController::class, 'posSessions'])->name('pos_sessions');
        
        Route::get('/all-stocks', [ExportController::class, 'allStocks'])->name('all_stocks');
        Route::get('/all-pos', [ExportController::class, 'allPos'])->name('all_pos');
    });

    // Imports
    Route::prefix('import')->name('import.')->group(function () {
        Route::post('/products', [ImportController::class, 'importProducts'])->name('products');
        Route::post('/stocks', [ImportController::class, 'importStocks'])->name('stocks');
    });
});

// Supply Chain Routes
Route::middleware(['auth'])->prefix('supply-chain')->name('supply_chain.')->group(function () {
    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Purchasing (Purchase Orders)
    Route::resource('purchasing', PurchasingController::class);

    // Receiving (Receiving Reports)
    Route::resource('receiving', ReceivingController::class)->only(['index', 'create', 'store', 'show']);

    // Forecasting & Alerts
    Route::prefix('forecast')->name('forecast.')->group(function () {
        Route::get('/', [ForecastController::class, 'index'])->name('index');
        Route::post('/generate', [ForecastController::class, 'generateForecast'])->name('generate');
        Route::post('/alert/{id}/resolve', [ForecastController::class, 'resolveAlert'])->name('resolve');
    });
});
