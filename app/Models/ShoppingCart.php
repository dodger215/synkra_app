<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class ShoppingCart extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'customer_id', 'session_id', 'items', 'coupon_code',
        'subtotal', 'total_amount', 'last_activity', 'expires_at',
        'abandoned_email_sent',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2', 'total_amount' => 'decimal:2',
        'last_activity' => 'datetime', 'expires_at' => 'datetime',
        'abandoned_email_sent' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}
