<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Customer extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'email', 'phone', 'first_name', 'last_name', 'company_name',
        'tax_id', 'customer_group', 'total_spent', 'total_orders',
        'average_order_value', 'last_order_at', 'first_order_at', 'lifetime_value',
        'loyalty_points', 'tier', 'tags', 'custom_fields', 'is_active',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2', 'average_order_value' => 'decimal:2',
        'lifetime_value' => 'decimal:2', 'last_order_at' => 'datetime',
        'first_order_at' => 'datetime', 'tags' => 'array',
        'custom_fields' => 'array', 'is_active' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function addresses() { return $this->hasMany(CustomerAddress::class); }
    public function interactions() { return $this->hasMany(CustomerInteraction::class); }
    public function posOrders() { return $this->hasMany(PosOrder::class); }
    public function ecommerceOrders() { return $this->hasMany(EcommerceOrder::class); }
    public function shoppingCarts() { return $this->hasMany(ShoppingCart::class); }
    public function productReviews() { return $this->hasMany(ProductReview::class); }
    public function loyaltyTransactions() { return $this->hasMany(LoyaltyTransaction::class); }
    public function segments() { return $this->belongsToMany(CustomerSegment::class, 'customer_segment_members', 'customer_id', 'segment_id'); }
}
