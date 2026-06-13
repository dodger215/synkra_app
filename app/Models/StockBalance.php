<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockBalance extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'product_id', 'location_id', 'bin_id', 'quantity_on_hand',
        'quantity_reserved', 'quantity_in_transit', 'quantity_damaged',
        'quantity_returned', 'last_counted_at',
    ];

    protected $casts = [
        'last_counted_at' => 'datetime',
    ];

    /** Computed: quantity_on_hand - quantity_reserved */
    public function getQuantityAvailableAttribute(): int
    {
        return $this->quantity_on_hand - $this->quantity_reserved;
    }

    /** Computed: reorder status based on product thresholds */
    public function getReorderStatusAttribute(): string
    {
        $product = $this->product;
        if (!$product) return 'unknown';
        if ($this->quantity_on_hand <= $product->reorder_point) return 'critical';
        if ($this->quantity_on_hand <= $product->min_stock_level) return 'low';
        return 'normal';
    }

    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
    public function bin() { return $this->belongsTo(StockBin::class, 'bin_id'); }
}
