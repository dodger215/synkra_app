<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockDamage extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'damage_number', 'product_id', 'location_id', 'quantity',
        'damage_type', 'severity', 'disposed_quantity', 'report_notes',
        'reported_by', 'reported_at', 'disposed_by', 'disposed_at',
        'insurance_claim_id', 'status',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'disposed_at' => 'datetime',
    ];

    /** Computed: remaining damaged quantity */
    public function getRemainingDamagedAttribute(): int
    {
        return ($this->quantity ?? 0) - ($this->disposed_quantity ?? 0);
    }

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
    public function reporter() { return $this->belongsTo(User::class, 'reported_by'); }
    public function disposer() { return $this->belongsTo(User::class, 'disposed_by'); }
}
