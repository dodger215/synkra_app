<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class ReorderAlert extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'product_id', 'location_id', 'alert_type',
        'current_quantity', 'threshold', 'suggested_order_quantity',
        'status', 'resolved_at',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
}
