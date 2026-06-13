<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class AdAutomationRule extends Model
{
    use HasUuidV5;

    protected $fillable = ['tenant_id', 'rule_name', 'condition', 'action', 'is_active', 'last_triggered_at'];

    protected $casts = [
        'condition' => 'array', 'action' => 'array',
        'is_active' => 'boolean', 'last_triggered_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
