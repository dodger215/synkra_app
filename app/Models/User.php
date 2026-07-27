<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

#[Fillable(['tenant_id', 'name', 'email', 'phone_number', 'mfa_type', 'mfa_code', 'mfa_expires_at', 'mfa_verified', 'password', 'google_id', 'role', 'permissions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids, HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'role' => \App\Enums\UserRole::class,
        ];
    }

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid5(Uuid::NAMESPACE_OID, uniqid('user_', true));
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Inventory Module
    public function stockMovementsCreated() { return $this->hasMany(StockMovement::class, 'created_by'); }
    public function stockMovementsApproved() { return $this->hasMany(StockMovement::class, 'approved_by'); }
    public function stockAdjustmentsRequested() { return $this->hasMany(StockAdjustment::class, 'requested_by'); }
    public function stockAdjustmentsApproved() { return $this->hasMany(StockAdjustment::class, 'approved_by'); }
    public function stockTransfersRequested() { return $this->hasMany(StockTransfer::class, 'requested_by'); }
    public function stockTransfersShipped() { return $this->hasMany(StockTransfer::class, 'shipped_by'); }
    public function stockTransfersReceived() { return $this->hasMany(StockTransfer::class, 'received_by'); }
    public function stockDamagesReported() { return $this->hasMany(StockDamage::class, 'reported_by'); }
    public function stockDamagesDisposed() { return $this->hasMany(StockDamage::class, 'disposed_by'); }
    public function stockReturnsCreated() { return $this->hasMany(StockReturn::class, 'created_by'); }
    public function stockCountSchedulesAssigned() { return $this->hasMany(StockCountSchedule::class, 'assigned_to'); }
    public function stockCountsCounted() { return $this->hasMany(StockCount::class, 'counted_by'); }
    public function stockCountsVerified() { return $this->hasMany(StockCount::class, 'verified_by'); }

    // POS Module
    public function posSessions() { return $this->hasMany(PosSession::class, 'cashier_id'); }

    // Marketing Module
    public function adCampaignsCreated() { return $this->hasMany(AdCampaign::class, 'created_by'); }

    // CRM Module
    public function customerSegmentsCreated() { return $this->hasMany(CustomerSegment::class, 'created_by'); }
    public function customerInteractionsCreated() { return $this->hasMany(CustomerInteraction::class, 'created_by'); }

    // Supply Chain Module
    public function purchaseOrdersCreated() { return $this->hasMany(PurchaseOrder::class, 'created_by'); }
    public function purchaseOrdersApproved() { return $this->hasMany(PurchaseOrder::class, 'approved_by'); }
    public function purchaseOrderItemsReceived() { return $this->hasMany(PurchaseOrderItem::class, 'received_by'); }
    public function receivingReportsReceived() { return $this->hasMany(ReceivingReport::class, 'received_by'); }

    // Reporting & Audit
    public function dashboardsCreated() { return $this->hasMany(Dashboard::class, 'created_by'); }
    public function reportsCreated() { return $this->hasMany(Report::class, 'created_by'); }
    public function auditLogs() { return $this->hasMany(AuditLog::class); }
}
