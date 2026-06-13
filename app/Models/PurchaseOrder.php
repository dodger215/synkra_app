<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class PurchaseOrder extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'po_number', 'supplier_id', 'order_date', 'expected_delivery_date',
        'actual_delivery_date', 'subtotal', 'shipping_cost', 'tax_amount', 'total_amount',
        'currency', 'payment_status', 'delivery_status', 'notes',
        'created_by', 'approved_by', 'approved_at', 'status',
    ];

    protected $casts = [
        'order_date' => 'date', 'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date', 'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2', 'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2', 'approved_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
    public function items() { return $this->hasMany(PurchaseOrderItem::class, 'po_id'); }
    public function receivingReports() { return $this->hasMany(ReceivingReport::class, 'po_id'); }
}
