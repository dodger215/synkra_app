<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class ProductLike extends Model
{
    use HasUuidV5;

    protected $fillable = ['customer_id', 'product_id'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
