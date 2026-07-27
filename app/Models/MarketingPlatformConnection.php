<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class MarketingPlatformConnection extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'platform_id', 'external_account_id', 'external_account_name',
        'access_token', 'refresh_token', 'expires_at', 'config', 'is_active'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'config' => 'array',
        'is_active' => 'boolean'
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function platform() { return $this->belongsTo(MarketingPlatform::class, 'platform_id'); }
}
