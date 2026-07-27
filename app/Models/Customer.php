<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\HasUuidV5;

class Customer extends Authenticatable
{
    use HasUuidV5, Notifiable;

    protected $fillable = [
        'email', 'password', 'phone', 'first_name', 'last_name', 'company_name',
        'tax_id', 'customer_group', 'total_spent', 'total_orders',
        'average_order_value', 'last_order_at', 'first_order_at', 'lifetime_value',
        'loyalty_points', 'tier', 'tags', 'custom_fields', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
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
    public function likedProducts() { return $this->hasMany(ProductLike::class); }
    public function followedTenants() { return $this->hasMany(TenantFollow::class); }
    public function loyaltyTransactions() { return $this->hasMany(LoyaltyTransaction::class); }
    public function segments() { return $this->belongsToMany(CustomerSegment::class, 'customer_segment_members', 'customer_id', 'segment_id'); }
}
