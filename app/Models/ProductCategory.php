<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class ProductCategory extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'name', 'description', 'parent_id', 'image_url',
        'sort_order', 'is_active', 'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function parent() { return $this->belongsTo(ProductCategory::class, 'parent_id'); }
    public function children() { return $this->hasMany(ProductCategory::class, 'parent_id'); }
    public function products() { return $this->hasMany(Product::class, 'category_id'); }
}
