<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class KpiMetric extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'metric_name', 'metric_category', 'current_value',
        'previous_value', 'target_value', 'unit', 'measured_at'
    ];

    protected $casts = [
        'current_value' => 'decimal:2',
        'previous_value' => 'decimal:2',
        'target_value' => 'decimal:2',
        'measured_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
