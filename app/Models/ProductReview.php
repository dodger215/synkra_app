<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class ProductReview extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'product_id', 'customer_id', 'order_id', 'rating', 'title',
        'comment', 'images', 'is_verified_purchase', 'status',
        'helpful_count', 'approved_at',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(EcommerceOrder::class, 'order_id'); }
}
