<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

class Tenant extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'subdomain',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function newUniqueId(): string
    {
        // Using UUID v5 as requested, falling back to a unique identifier for the "name"
        return (string) Uuid::uuid5(Uuid::NAMESPACE_OID, uniqid('tenant_', true));
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function services()
    {
        return $this->hasMany(TenantService::class);
    }

    // Inventory Module
    public function productCategories() { return $this->hasMany(ProductCategory::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function stockLocations() { return $this->hasMany(StockLocation::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function stockAdjustmentReasons() { return $this->hasMany(StockAdjustmentReason::class); }
    public function stockAdjustments() { return $this->hasMany(StockAdjustment::class); }
    public function stockTransfers() { return $this->hasMany(StockTransfer::class); }
    public function stockDamages() { return $this->hasMany(StockDamage::class); }
    public function stockReturns() { return $this->hasMany(StockReturn::class); }
    public function stockCountSchedules() { return $this->hasMany(StockCountSchedule::class); }
    public function stockCounts() { return $this->hasMany(StockCount::class); }

    // POS Module
    public function posDevices() { return $this->hasMany(PosDevice::class); }
    public function posSessions() { return $this->hasMany(PosSession::class); }
    public function posOrders() { return $this->hasMany(PosOrder::class); }

    // CRM Module
    public function customers() { return $this->hasMany(Customer::class); }
    public function customerSegments() { return $this->hasMany(CustomerSegment::class); }
    public function loyaltyPrograms() { return $this->hasMany(LoyaltyProgram::class); }

    // Marketing Module
    public function adCampaigns() { return $this->hasMany(AdCampaign::class); }
    public function realTimeProductFeeds() { return $this->hasMany(RealTimeProductFeed::class); }
    public function adAutomationRules() { return $this->hasMany(AdAutomationRule::class); }

    // Supply Chain Module
    public function suppliers() { return $this->hasMany(Supplier::class); }
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
    public function reorderAlerts() { return $this->hasMany(ReorderAlert::class); }

    // Ecommerce & Payments Module
    public function ecommerceStores() { return $this->hasMany(EcommerceStore::class); }
    public function ecommerceOrders() { return $this->hasMany(EcommerceOrder::class); }
    public function shoppingCarts() { return $this->hasMany(ShoppingCart::class); }
    public function coupons() { return $this->hasMany(Coupon::class); }
    public function vendors() { return $this->hasMany(Vendor::class); }
    public function subaccounts() { return $this->hasMany(TenantSubaccount::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
    public function refunds() { return $this->hasMany(Refund::class); }
    public function contacts() { return $this->hasMany(TenantContact::class); }

    // Reporting & Analytics
    public function dashboards() { return $this->hasMany(Dashboard::class); }
    public function reports() { return $this->hasMany(Report::class); }
    public function kpiMetrics() { return $this->hasMany(KpiMetric::class); }
    public function auditLogs() { return $this->hasMany(AuditLog::class); }
    public function systemNotifications() { return $this->hasMany(SystemNotification::class); }

}

