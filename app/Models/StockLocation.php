<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockLocation extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'name', 'location_type', 'address', 'is_default', 'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function bins() { return $this->hasMany(StockBin::class, 'location_id'); }
    public function stockBalances() { return $this->hasMany(StockBalance::class, 'location_id'); }
    public function posDevices() { return $this->hasMany(PosDevice::class, 'location_id'); }
}
