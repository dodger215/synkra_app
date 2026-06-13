<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class MarketingPlatform extends Model
{
    use HasUuidV5;

    protected $fillable = ['platform_name', 'api_config_template', 'is_available'];
    protected $casts = ['api_config_template' => 'array', 'is_available' => 'boolean'];

    public function campaigns() { return $this->hasMany(AdCampaign::class, 'platform_id'); }
    public function productFeeds() { return $this->hasMany(RealTimeProductFeed::class, 'platform_id'); }
}
