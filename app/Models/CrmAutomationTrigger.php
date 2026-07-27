<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class CrmAutomationTrigger extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'name', 'event_type', 'action_type', 'action_config', 'is_active'
    ];

    protected $casts = [
        'action_config' => 'array',
        'is_active' => 'boolean'
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
