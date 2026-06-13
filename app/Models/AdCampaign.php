<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class AdCampaign extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'platform_id', 'campaign_name', 'objective', 'daily_budget',
        'lifetime_budget', 'start_date', 'end_date', 'targeting', 'creative_assets',
        'status', 'external_campaign_id', 'external_account_id', 'created_by', 'last_synced_at',
    ];

    protected $casts = [
        'daily_budget' => 'decimal:2', 'lifetime_budget' => 'decimal:2',
        'start_date' => 'datetime', 'end_date' => 'datetime',
        'targeting' => 'array', 'creative_assets' => 'array',
        'last_synced_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function platform() { return $this->belongsTo(MarketingPlatform::class, 'platform_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function adSets() { return $this->hasMany(AdSet::class, 'campaign_id'); }
}
