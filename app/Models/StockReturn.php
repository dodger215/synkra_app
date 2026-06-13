<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockReturn extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'return_number', 'original_reference_type', 'original_reference_id',
        'product_id', 'location_id', 'quantity', 'return_reason', 'condition',
        'restocked_quantity', 'restocked_at', 'refund_amount', 'created_by', 'status',
    ];

    protected $casts = [
        'restocked_at' => 'datetime',
        'refund_amount' => 'decimal:2',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
