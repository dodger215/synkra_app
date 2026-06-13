<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockCount extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'schedule_id', 'count_number', 'location_id', 'product_id',
        'expected_quantity', 'counted_quantity', 'variance_percentage',
        'counted_by', 'counted_at', 'verified_by', 'verified_at', 'notes', 'status',
    ];

    protected $casts = [
        'variance_percentage' => 'decimal:2',
        'counted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /** Computed: variance = counted - expected */
    public function getVarianceAttribute(): ?int
    {
        if ($this->counted_quantity === null || $this->expected_quantity === null) return null;
        return $this->counted_quantity - $this->expected_quantity;
    }

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function schedule() { return $this->belongsTo(StockCountSchedule::class, 'schedule_id'); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
    public function product() { return $this->belongsTo(Product::class); }
    public function counter() { return $this->belongsTo(User::class, 'counted_by'); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }
}
