<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class PosDevice extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'device_name', 'location_id', 'serial_number',
        'connection_type', 'ip_address', 'port', 'status', 'last_sync_at'
    ];
    protected $casts = ['last_sync_at' => 'datetime'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
    public function sessions() { return $this->hasMany(PosSession::class, 'pos_device_id'); }
}
