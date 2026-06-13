<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Vendor extends Model
{
    use HasUuidV5;

    protected $fillable = ['tenant_id', 'name', 'email', 'phone', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function subaccounts() { return $this->hasMany(TenantSubaccount::class, 'vendor_id'); }
}
