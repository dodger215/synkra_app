<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Product extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'supplier_id', 'sku', 'barcode', 'name', 'slug', 'description', 'category_id',
        'brand', 'unit_type', 'unit_price', 'cost_price', 'weight_kg',
        'dimensions', 'min_stock_level', 'max_stock_level', 'reorder_point',
        'reorder_quantity', 'is_active', 'tax_rate', 'attributes', 'images',
        'is_network_available', 'supply_price', 'supply_min_order', 'supply_max_order', 'supply_buffer_percent',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'supply_price' => 'decimal:2',
        'supply_min_order' => 'decimal:2',
        'supply_max_order' => 'decimal:2',
        'supply_buffer_percent' => 'decimal:2',
        'weight_kg' => 'decimal:3',
        'dimensions' => 'array',
        'is_active' => 'boolean',
        'is_network_available' => 'boolean',
        'tax_rate' => 'decimal:2',
        'attributes' => 'array',
        'images' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name) . '-' . \Illuminate\Support\Str::random(5);
            }
        });
    }

    public function reviews() { return $this->hasMany(ProductReview::class); }
    public function likes() { return $this->hasMany(ProductLike::class); }

    public function getRouteKey()
    {
        return $this->slug ?: $this->id;
    }

    public function imageUrl(int $index = 0): ?string
    {
        $images = $this->images ?? [];

        if (! isset($images[$index])) {
            return null;
        }

        return '/storage/' . ltrim($images[$index], '/');
    }

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function category() { return $this->belongsTo(ProductCategory::class, 'category_id'); }
    public function stockBalances() { return $this->hasMany(StockBalance::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function reorderAlerts() { return $this->hasMany(ReorderAlert::class); }
    public function adSets() { return $this->hasMany(AdSet::class); }
    public function productFeeds() { return $this->hasMany(RealTimeProductFeed::class); }
}
