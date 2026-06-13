<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockAdjustment extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'adjustment_number', 'product_id', 'location_id', 'bin_id',
        'reason_id', 'old_quantity', 'new_quantity', 'quantity_change',
        'adjustment_type', 'unit_cost', 'notes', 'status',
        'requested_by', 'requested_at', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
    public function bin() { return $this->belongsTo(StockBin::class, 'bin_id'); }
    public function reason() { return $this->belongsTo(StockAdjustmentReason::class, 'reason_id'); }
    public function requester() { return $this->belongsTo(User::class, 'requested_by'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
