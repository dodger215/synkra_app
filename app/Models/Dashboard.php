<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Dashboard extends Model
{
    use HasUuidV5;

    protected $fillable = ['tenant_id', 'dashboard_name', 'layout', 'is_default', 'created_by'];

    protected $casts = [
        'layout' => 'array',
        'is_default' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
