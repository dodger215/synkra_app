<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Report extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'report_name', 'report_type', 'parameters',
        'schedule', 'last_run_at', 'last_run_status', 'recipients',
        'created_by'
    ];

    protected $casts = [
        'parameters' => 'array',
        'recipients' => 'array',
        'last_run_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
