<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class EcommerceOrder extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'store_id', 'order_number', 'customer_id', 'session_id',
        'subtotal', 'discount_amount', 'shipping_cost', 'tax_amount',
        'total_amount', 'payment_status', 'fulfillment_status',
        'shipping_address', 'billing_address', 'customer_notes',
        'admin_notes', 'ip_address', 'user_agent', 'coupon_code',
        'ordered_at', 'paid_at', 'fulfilled_at', 'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2', 'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2', 'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array', 'billing_address' => 'array',
        'ordered_at' => 'datetime', 'paid_at' => 'datetime',
        'fulfilled_at' => 'datetime', 'cancelled_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function store() { return $this->belongsTo(EcommerceStore::class, 'store_id'); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(EcommerceOrderItem::class, 'order_id'); }
    public function reviews() { return $this->hasMany(ProductReview::class, 'order_id'); }
}
