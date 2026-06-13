<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Coupon extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'code', 'discount_type', 'discount_value',
        'min_order_amount', 'max_discount_amount', 'usage_limit',
        'used_count', 'per_customer_limit', 'start_date', 'end_date',
        'applicable_products', 'applicable_categories', 'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2', 'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'date', 'end_date' => 'date',
        'applicable_products' => 'array', 'applicable_categories' => 'array',
        'is_active' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
