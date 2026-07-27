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
use App\Http\Controllers\SupplyChain\SupplierContractController;
use App\Http\Controllers\SupplyChain\ShipmentController;
use App\Http\Controllers\SupplyChain\ScmReportController;

// CRM Controllers
use App\Http\Controllers\Crm\CustomerController;
use App\Http\Controllers\Crm\SegmentController;
use App\Http\Controllers\Crm\InteractionController;
use App\Http\Controllers\Crm\LoyaltyController;
use App\Http\Controllers\Crm\CrmAnalyticsController;
use App\Http\Controllers\Crm\AutomationController;

// Marketing Controllers
use App\Http\Controllers\Marketing\CampaignController;
use App\Http\Controllers\Marketing\PlatformConnectionController;
use App\Http\Controllers\Marketing\SubscriptionController;

// Ecommerce Controllers
use App\Http\Controllers\Ecommerce\EcommerceStoreController;
use App\Http\Controllers\Ecommerce\EcommercePageController;
use App\Http\Controllers\Ecommerce\EcommerceOrderController;
use App\Http\Controllers\Ecommerce\EcommerceReviewController;
use App\Http\Controllers\Ecommerce\EcommerceAnalyticsController;

Route::get('/', [\App\Http\Controllers\Home\PageDisplayController::class, 'index'])->name('home.index');
Route::get('/shops', [\App\Http\Controllers\Home\PageDisplayController::class, 'shops'])->name('home.shops');
Route::get('/shop/{tenant}', [\App\Http\Controllers\Home\PageDisplayController::class, 'shop'])->name('home.shop');
Route::get('/shop/{tenant}/p/{product}', [\App\Http\Controllers\Home\PageDisplayController::class, 'productDetails'])->name('home.product.details');

// Cart & Checkout for Home Marketplace
Route::get('/cart', [\App\Http\Controllers\Home\ShopCartController::class, 'index'])->name('home.cart.index');
Route::get('/cart/api', [\App\Http\Controllers\Home\ShopCartController::class, 'getCart'])->name('home.cart.get');
Route::post('/cart/add', [\App\Http\Controllers\Home\ShopCartController::class, 'addToCart'])->name('home.cart.add');
Route::post('/cart/remove', [\App\Http\Controllers\Home\ShopCartController::class, 'removeFromCart'])->name('home.cart.remove');
Route::post('/cart/update', [\App\Http\Controllers\Home\ShopCartController::class, 'updateQuantity'])->name('home.cart.update');

Route::middleware('auth:customer')->group(function () {
    Route::get('/shop/{tenant}/checkout', [\App\Http\Controllers\Home\ShopCheckoutController::class, 'showCheckout'])->name('home.checkout.show');
    Route::post('/shop/{tenant}/checkout', [\App\Http\Controllers\Home\ShopCheckoutController::class, 'processPayment'])->name('home.checkout.process');
    Route::post('/shop/{tenant}/checkout/quote', [\App\Http\Controllers\Home\ShopCheckoutController::class, 'getDeliveryQuote'])->name('home.checkout.quote');
    Route::post('/shop/{tenant}/checkout/pickup-distance', [\App\Http\Controllers\Home\ShopCheckoutController::class, 'getPickupDistance'])->name('home.checkout.pickup_distance');
    Route::get('/shop/{tenant}/checkout/callback', [\App\Http\Controllers\Home\ShopCheckoutController::class, 'callback'])->name('home.checkout.callback');
    Route::get('/shop/{tenant}/order/{order}/tracking', [\App\Http\Controllers\Home\ShopCheckoutController::class, 'trackOrder'])->name('home.order.tracking');
    Route::post('/shop/{tenant}/order/{order}/arrived', [\App\Http\Controllers\Home\ShopCheckoutController::class, 'confirmArrival'])->name('home.order.arrived');
    Route::post('/shop/{tenant}/order/{order}/collected', [\App\Http\Controllers\Home\ShopCheckoutController::class, 'confirmCollection'])->name('home.order.collected');
});

// Customer Auth for Home Marketplace
Route::get('/customer/login', [\App\Http\Controllers\Home\CustomerAuthController::class, 'showLogin'])->name('home.customer.login');
Route::post('/customer/login', [\App\Http\Controllers\Home\CustomerAuthController::class, 'login']);

Route::prefix('customer')->name('home.customer.')->group(function () {
    Route::get('/register', [\App\Http\Controllers\Home\CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Home\CustomerAuthController::class, 'register']);
    Route::post('/logout', [\App\Http\Controllers\Home\CustomerAuthController::class, 'logout'])->name('logout');

    // Google Auth for Customer
    Route::get('/auth/google', [\App\Http\Controllers\Home\CustomerAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [\App\Http\Controllers\Home\CustomerAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Protected Customer Routes
    Route::middleware('auth:customer')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Home\CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Home\CustomerDashboardController::class, 'orders'])->name('orders');
        Route::get('/saved-items', [\App\Http\Controllers\Home\CustomerDashboardController::class, 'savedItems'])->name('saved_items');
        Route::get('/settings', [\App\Http\Controllers\Home\CustomerDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Home\CustomerDashboardController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/password', [\App\Http\Controllers\Home\CustomerDashboardController::class, 'updatePassword'])->name('settings.password');

        // Marketplace Interactions
        Route::post('/product/{product}/like', [\App\Http\Controllers\Home\MarketplaceInteractionController::class, 'toggleLike'])->name('product.like');
        Route::post('/tenant/{tenant}/follow', [\App\Http\Controllers\Home\MarketplaceInteractionController::class, 'toggleFollow'])->name('tenant.follow');
        Route::post('/product/{product}/review', [\App\Http\Controllers\Home\MarketplaceInteractionController::class, 'storeReview'])->name('product.review');
    });
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

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

// MFA Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/verify-mfa', [\App\Http\Controllers\Auth\MfaController::class, 'showVerifyForm'])->name('mfa.verify');
    Route::post('/verify-mfa', [\App\Http\Controllers\Auth\MfaController::class, 'verify'])->name('mfa.verify.submit');
    Route::post('/resend-mfa', [\App\Http\Controllers\Auth\MfaController::class, 'resend'])->name('mfa.resend');
});

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/metrics', [\App\Http\Controllers\DashboardController::class, 'metrics'])->name('dashboard.metrics');
});

// Settings Routes
Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {
    // Hub
    Route::get('/', [\App\Http\Controllers\Settings\SettingsController::class, 'index'])->name('index');
    Route::post('/theme', [\App\Http\Controllers\Settings\SettingsController::class, 'storeTheme'])->name('theme.store');

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
        Route::post('/subaccounts/resolve', [\App\Http\Controllers\Settings\TenantSubaccountController::class, 'resolve'])->name('subaccounts.resolve');
        Route::post('/subaccounts', [\App\Http\Controllers\Settings\TenantSubaccountController::class, 'store'])->name('subaccounts.store');
        Route::put('/subaccounts/{id}', [\App\Http\Controllers\Settings\TenantSubaccountController::class, 'update'])->name('subaccounts.update');
        Route::delete('/subaccounts/{id}', [\App\Http\Controllers\Settings\TenantSubaccountController::class, 'destroy'])->name('subaccounts.destroy');
    });
});

// Product Service Routes
Route::middleware(['auth'])->prefix('product-service')->name('product_service.')->group(function () {
    // Categories
    Route::resource('categories', CategoriesController::class);

    // Products (InventoryController handles CRUD for products)
    Route::resource('products', InventoryController::class);
    // Save product draft to session before quick-creating a category
    Route::post('/products/draft/save', function (\Illuminate\Http\Request $request) {
        session()->put('product_create_draft', $request->except(['_token']));
        return redirect()->route('product_service.categories.create', ['from_product' => 1]);
    })->middleware('auth')->name('products.draft.save');

    // Add to routes file



    // Stocks Management
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StocksManagementController::class, 'index'])->name('index');
        Route::get('/movements', [StocksManagementController::class, 'movements'])->name('movements');
        Route::get('/adjustments', [StocksManagementController::class, 'adjustments'])->name('adjustments.index');
        Route::get('/transfers', [StocksManagementController::class, 'transfers'])->name('transfers.index');
        Route::get('/damages', [StocksManagementController::class, 'damages'])->name('damages.index');
        Route::get('/counts', [StocksManagementController::class, 'counts'])->name('counts.index');
        Route::get('/returns', [StocksManagementController::class, 'returns'])->name('returns.index');
        Route::get('/reorder-alerts', [StocksManagementController::class, 'reorderAlerts'])->name('reorder_alerts.index');
        Route::post('/reorder-alerts/{id}/resolve', [StocksManagementController::class, 'resolveReorderAlert'])->name('reorder_alerts.resolve');

        Route::get('/receive/create', [StocksManagementController::class, 'createReceive'])->name('receive.create');
        Route::post('/receive', [StocksManagementController::class, 'storeReceive'])->name('receive.store');

        Route::get('/issue/create', [StocksManagementController::class, 'createIssue'])->name('issue.create');
        Route::post('/issue', [StocksManagementController::class, 'storeIssue'])->name('issue.store');

        Route::get('/transfer/create', [StocksManagementController::class, 'createTransfer'])->name('transfer.create');
        Route::post('/transfer', [StocksManagementController::class, 'storeTransfer'])->name('transfer.store');

        Route::get('/adjustment/create', [StocksManagementController::class, 'createAdjustment'])->name('adjustment.create');
        Route::post('/adjustment', [StocksManagementController::class, 'storeAdjustment'])->name('adjustment.store');
        Route::post('/adjustment-reasons', [StocksManagementController::class, 'storeAdjustmentReason'])->name('adjustment_reasons.store');

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

        Route::post('/import/{productId}', [StocksManagementController::class, 'importStock'])->name('import');

        // Manufacturing / BOM Routes
        Route::get('/bill-of-materials', [StocksManagementController::class, 'bomIndex'])->name('bom.index');
        Route::post('/bill-of-materials', [StocksManagementController::class, 'storeBom'])->name('bom.store');
        Route::get('/production-orders', [StocksManagementController::class, 'productionOrders'])->name('production.index');
        Route::post('/production-orders', [StocksManagementController::class, 'storeProductionOrder'])->name('production.store');

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
        Route::get('/daily-sales', [PosController::class, 'dailySales'])->name('daily-sales');
        Route::get('/devices', [PosController::class, 'devices'])->name('devices');
        Route::get('/drawer-access', [PosController::class, 'drawerAccess'])->name('drawer-access');

        // Restaurant POS Routes
        Route::get('/tables', [PosController::class, 'tables'])->name('tables');
        Route::post('/tables', [PosController::class, 'storeTable'])->name('tables.store');
        Route::get('/kitchen', [PosController::class, 'kitchen'])->name('kitchen');
        Route::post('/kitchen/{id}/complete', [PosController::class, 'completeKitchenOrder'])->name('kitchen.complete');

        // POS Device Integrations
        Route::post('/device/store', [PosController::class, 'storeDevice'])->name('device.store');
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
    Route::post('/suppliers/{id}/link-product', [SupplierController::class, 'linkProduct'])->name('suppliers.link_product');

    // Purchasing (Purchase Orders)
    Route::resource('purchasing', PurchasingController::class);
    Route::post('purchasing/{id}/approve', [PurchasingController::class, 'approve'])->name('purchasing.approve');
    Route::post('purchasing/{id}/cancel', [PurchasingController::class, 'cancel'])->name('purchasing.cancel');

    // Receiving (Receiving Reports)
    Route::resource('receiving', ReceivingController::class)->only(['index', 'create', 'store', 'show']);

    // Contracts
    Route::resource('contracts', SupplierContractController::class);

    // Forecasting & Alerts
    Route::prefix('forecast')->name('forecast.')->group(function () {
        Route::get('/', [ForecastController::class, 'index'])->name('index');
        Route::post('/generate', [ForecastController::class, 'generateForecast'])->name('generate');
        Route::post('/alert/{id}/resolve', [ForecastController::class, 'resolveAlert'])->name('resolve');
    });

    // Shipments & Tracking
    Route::prefix('shipments')->name('shipments.')->group(function () {
        Route::get('/', [ShipmentController::class, 'index'])->name('index');
        Route::get('/{id}', [ShipmentController::class, 'show'])->name('show');
        Route::post('/{id}/status', [ShipmentController::class, 'updateStatus'])->name('update-status');
    });

    // Supplier Mode Specific
    Route::get('/product-supplier-list', [SupplierController::class, 'supplierList'])->name('supplier_list.index');
    Route::get('/import-stocks', [SupplierController::class, 'importStocks'])->name('import_stocks.index');
    Route::post('/import-stocks', [SupplierController::class, 'processImportStocks'])->name('import_stocks.process');
    Route::delete('/import-stocks/{id}', [SupplierController::class, 'removeStockFromSupply'])->name('import_stocks.remove');

    // Approvals
    Route::get('/approvals', [SupplierController::class, 'approvals'])->name('approvals.index');
    Route::post('/approvals/{id}/approve', [SupplierController::class, 'approveRequest'])->name('approvals.approve');
    Route::post('/approvals/{id}/reject', [SupplierController::class, 'rejectRequest'])->name('approvals.reject');

    // Reports & Exports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ScmReportController::class, 'index'])->name('index');
        Route::get('/suppliers', [ScmReportController::class, 'exportSuppliers'])->name('suppliers');
        Route::get('/purchase-orders', [ScmReportController::class, 'exportPurchaseOrders'])->name('purchase-orders');
    });
});

// CRM Routes
Route::middleware(['auth'])->prefix('crm')->name('crm.')->group(function () {
    // Customers
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{id}/message', [CustomerController::class, 'sendMessage'])->name('customers.message');

    // Segments
    Route::resource('segments', SegmentController::class);

    // Interactions
    Route::resource('interactions', InteractionController::class);
    Route::get('/communication-history', [InteractionController::class, 'history'])->name('communication_history');

    // Loyalty
    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [LoyaltyController::class, 'index'])->name('index');
        Route::get('/programs', [LoyaltyController::class, 'programs'])->name('programs.index');
        Route::post('/programs', [LoyaltyController::class, 'storeProgram'])->name('programs.store');
        Route::post('/adjust-points', [LoyaltyController::class, 'adjustPoints'])->name('adjust_points');
    });

    // Automation
    Route::resource('automations', AutomationController::class);

    // Analytics
    Route::get('/analytics', [CrmAnalyticsController::class, 'index'])->name('analytics');
});

// Marketing Routes
Route::middleware(['auth'])->prefix('marketing')->name('marketing.')->group(function () {
    Route::resource('campaigns', CampaignController::class);

    Route::get('/connections', [PlatformConnectionController::class, 'index'])->name('connections.index');
    Route::get('/connections/{platform}/connect', [PlatformConnectionController::class, 'connect'])->name('connections.connect');
    Route::get('/connections/callback', [PlatformConnectionController::class, 'callback'])->name('connections.callback');
    Route::delete('/connections/{id}', [PlatformConnectionController::class, 'disconnect'])->name('connections.disconnect');

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::get('/subscriptions/callback', [SubscriptionController::class, 'callback'])->name('subscriptions.callback');
});

// Ecommerce Routes
Route::middleware(['auth'])->prefix('ecommerce')->name('ecommerce.')->group(function () {
    Route::resource('stores', EcommerceStoreController::class);

    Route::prefix('stores/{store}')->group(function () {
        Route::resource('pages', EcommercePageController::class);
        Route::get('pages/{page}/builder', [EcommercePageController::class, 'builder'])->name('pages.builder');
        Route::post('pages/{page}/builder/save', [EcommercePageController::class, 'saveContent'])->name('pages.save-content');
        Route::get('templates', [EcommercePageController::class, 'templates'])->name('templates.index');
        Route::post('templates/apply', [EcommercePageController::class, 'applyTemplate'])->name('templates.apply');

        Route::get('orders', [EcommerceOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [EcommerceOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [EcommerceOrderController::class, 'updateStatus'])->name('orders.update-status');

        // Reviews
        Route::get('reviews', [EcommerceReviewController::class, 'index'])->name('reviews.index');
        Route::post('reviews/{review}/approve', [EcommerceReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/{review}/reject', [EcommerceReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('reviews/{review}', [EcommerceReviewController::class, 'destroy'])->name('reviews.destroy');

        // Analytics
        Route::get('analytics', [EcommerceAnalyticsController::class, 'index'])->name('analytics');
    });
});

// Public Storefront Routes
Route::prefix('s/{store}')->name('storefront.')->group(function () {
    Route::get('/', [EcommercePageController::class, 'show'])->name('home');
    Route::get('/p/{page}', [EcommercePageController::class, 'show'])->name('page');

    // Customer Auth
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

    // Checkout
    Route::post('/checkout', [EcommerceCheckoutController::class, 'process'])->name('checkout');
});

// Reporting Routes
Route::middleware(['auth'])->prefix('reporting')->name('reporting.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Reporting\ReportingController::class, 'index'])->name('index');
    Route::get('/dashboards', [\App\Http\Controllers\Reporting\ReportingController::class, 'dashboards'])->name('dashboards');
    Route::get('/reports', [\App\Http\Controllers\Reporting\ReportingController::class, 'reports'])->name('reports');
    Route::get('/kpi', [\App\Http\Controllers\Reporting\ReportingController::class, 'kpi'])->name('kpi');
    Route::get('/audit-logs', [\App\Http\Controllers\Reporting\ReportingController::class, 'auditLogs'])->name('audit_logs');
});

Route::get('/ui', function () {
    return view('ui.index');
});

