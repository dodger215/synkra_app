<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class AdSet extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'campaign_id', 'product_id', 'ad_set_name', 'bid_strategy',
        'bid_amount', 'audience', 'placements', 'status', 'external_ad_set_id',
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2', 'audience' => 'array', 'placements' => 'array',
    ];

    public function campaign() { return $this->belongsTo(AdCampaign::class, 'campaign_id'); }
    public function product() { return $this->belongsTo(Product::class); }
    public function performances() { return $this->hasMany(AdPerformance::class, 'ad_set_id'); }
}
