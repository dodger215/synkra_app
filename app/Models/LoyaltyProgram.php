<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class LoyaltyProgram extends Model
{
    use HasUuidV5;

    protected $fillable = ['tenant_id', 'program_name', 'points_per_dollar', 'points_expiry_days', 'reward_tiers', 'is_active'];

    protected $casts = [
        'points_per_dollar' => 'decimal:2', 'reward_tiers' => 'array', 'is_active' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function transactions() { return $this->hasMany(LoyaltyTransaction::class, 'program_id'); }
}
