<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockMovement extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'product_id', 'location_id', 'bin_id', 'movement_type_id',
        'movement_type', 'quantity', 'previous_balance', 'new_balance',
        'reference_type', 'reference_id', 'unit_cost', 'batch_number',
        'expiry_date', 'notes', 'created_by', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
    public function bin() { return $this->belongsTo(StockBin::class, 'bin_id'); }
    public function movementType() { return $this->belongsTo(StockMovementType::class, 'movement_type_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
