<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class SystemNotification extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'notification_type', 'severity', 'title', 'message',
        'is_read', 'reference_type', 'reference_id', 'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
