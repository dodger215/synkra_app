<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Crm\CustomerController;
use App\Http\Controllers\Api\Crm\InteractionController;
use App\Http\Controllers\Api\Ecommerce\StoreController;
use App\Http\Controllers\Api\Ecommerce\OrderController as EcommerceOrderController;
use App\Http\Controllers\Api\Marketing\CampaignController;
use App\Http\Controllers\Api\Marketing\ConnectionController;
use App\Http\Controllers\Api\Settings\TenantController;
use App\Http\Controllers\Api\ProductService\InventoryController;
use App\Http\Controllers\Api\ProductService\PosController;
use App\Http\Controllers\Api\SupplyChain\SupplierController;
use App\Http\Controllers\Api\SupplyChain\PurchasingController;
use App\Http\Controllers\Api\InviteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    // User & Tenant
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/workspace', [TenantController::class, 'show']);
        Route::put('/workspace', [TenantController::class, 'update']);
    });

    // CRM
    Route::prefix('crm')->group(function () {
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('interactions', InteractionController::class)->only(['index', 'store']);
    });

    // Ecommerce
    Route::prefix('ecommerce')->group(function () {
        Route::get('/stores', [StoreController::class, 'index']);
        Route::get('/stores/{id}', [StoreController::class, 'show']);
        Route::get('/stores/{storeId}/orders', [EcommerceOrderController::class, 'index']);
        Route::get('/stores/{storeId}/orders/{orderId}', [EcommerceOrderController::class, 'show']);
        Route::patch('/stores/{storeId}/orders/{orderId}/status', [EcommerceOrderController::class, 'updateStatus']);
    });

    // Marketing
    Route::prefix('marketing')->group(function () {
        Route::apiResource('campaigns', CampaignController::class);
        Route::get('/connections', [ConnectionController::class, 'index']);
    });

    // Product Service
    Route::prefix('product-service')->group(function () {
        Route::apiResource('products', InventoryController::class);
        Route::get('/pos/orders', [PosController::class, 'orders']);
        Route::get('/pos/orders/{id}', [PosController::class, 'showOrder']);
    });

    // Supply Chain
    Route::prefix('supply-chain')->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'index']);
        Route::get('/suppliers/{id}', [SupplierController::class, 'show']);
        Route::get('/purchasing', [PurchasingController::class, 'index']);
        Route::get('/purchasing/{id}', [PurchasingController::class, 'show']);
    });

    // Invites
    Route::apiResource('invites', InviteController::class)->only(['index', 'store']);

});
