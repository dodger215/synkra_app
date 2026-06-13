<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class TenantContact extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'contact_type', 'platform', 'handle', 'url', 'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
