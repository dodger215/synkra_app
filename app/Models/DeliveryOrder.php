<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class DeliveryOrder extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'ecommerce_order_id', 'tenant_id', 'provider', 'external_id',
        'quote_id', 'status', 'fee', 'currency', 'tracking_url',
        'pickup_address', 'pickup_lat', 'pickup_lng',
        'dropoff_address', 'dropoff_lat', 'dropoff_lng',
        'courier_name', 'courier_phone', 'estimated_minutes',
        'raw_response'
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'pickup_lat' => 'decimal:8',
        'pickup_lng' => 'decimal:8',
        'dropoff_lat' => 'decimal:8',
        'dropoff_lng' => 'decimal:8',
        'raw_response' => 'array',
    ];

    public function ecommerceOrder() { return $this->belongsTo(EcommerceOrder::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}
