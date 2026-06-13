<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

class Invite extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'invited_by',
        'email',
        'role',
        'token',
        'permissions',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'role' => \App\Enums\UserRole::class,
        'expires_at' => 'datetime',
    ];

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid5(Uuid::NAMESPACE_OID, uniqid('invite_', true));
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }
}
