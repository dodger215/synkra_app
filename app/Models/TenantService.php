<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

class TenantService extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'service_name',
        'sub_category',
        'is_active',
        'config',
        'activated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
        'activated_at' => 'datetime',
    ];

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid5(Uuid::NAMESPACE_OID, uniqid('service_', true));
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
