<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class MarketingSubscription extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'plan_name', 'monthly_price', 'features', 'starts_at', 'ends_at', 'status'
    ];

    protected $casts = [
        'features' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
