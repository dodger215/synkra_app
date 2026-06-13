<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

trait HasUuidV5
{
    use HasUuids;

    public function newUniqueId(): string
    {
        $prefix = strtolower(class_basename(static::class));
        return (string) Uuid::uuid5(Uuid::NAMESPACE_OID, uniqid("{$prefix}_", true));
    }
}
