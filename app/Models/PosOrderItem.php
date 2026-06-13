<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class PosOrderItem extends Model
{
    use HasUuidV5;

    protected $fillable = ['pos_order_id', 'product_id', 'quantity', 'unit_price', 'discount_amount', 'total_price'];

    protected $casts = [
        'unit_price' => 'decimal:2', 'discount_amount' => 'decimal:2', 'total_price' => 'decimal:2',
    ];

    public function order() { return $this->belongsTo(PosOrder::class, 'pos_order_id'); }
    public function product() { return $this->belongsTo(Product::class); }
}
