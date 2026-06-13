<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class EcommerceOrderItem extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'order_id', 'product_id', 'variant_id', 'quantity', 'unit_price',
        'discount_amount', 'total_price', 'product_data_snapshot',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2', 'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2', 'product_data_snapshot' => 'array',
    ];

    public function order() { return $this->belongsTo(EcommerceOrder::class, 'order_id'); }
    public function product() { return $this->belongsTo(Product::class); }
}
