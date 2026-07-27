<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

class PosTable extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'name',
        'capacity',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
