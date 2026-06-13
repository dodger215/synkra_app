<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class RealTimeProductFeed extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'product_id', 'platform_id', 'feed_status', 'external_product_id',
        'product_url', 'sync_token', 'last_synced_at', 'sync_error', 'is_live',
    ];

    protected $casts = ['last_synced_at' => 'datetime', 'is_live' => 'boolean'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function platform() { return $this->belongsTo(MarketingPlatform::class, 'platform_id'); }
}
