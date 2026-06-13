<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Product extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'sku', 'barcode', 'name', 'description', 'category_id',
        'brand', 'unit_type', 'unit_price', 'cost_price', 'weight_kg',
        'dimensions', 'min_stock_level', 'max_stock_level', 'reorder_point',
        'reorder_quantity', 'is_active', 'tax_rate', 'attributes', 'images',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight_kg' => 'decimal:3',
        'dimensions' => 'array',
        'is_active' => 'boolean',
        'tax_rate' => 'decimal:2',
        'attributes' => 'array',
        'images' => 'array',
    ];

    public function imageUrl(int $index = 0): ?string
    {
        $images = $this->images ?? [];

        if (! isset($images[$index])) {
            return null;
        }

        return '/storage/' . ltrim($images[$index], '/');
    }

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function category() { return $this->belongsTo(ProductCategory::class, 'category_id'); }
    public function stockBalances() { return $this->hasMany(StockBalance::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function adSets() { return $this->hasMany(AdSet::class); }
    public function productFeeds() { return $this->hasMany(RealTimeProductFeed::class); }
}
