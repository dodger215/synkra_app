<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class TenantFollow extends Model
{
    use HasUuidV5;

    protected $fillable = ['customer_id', 'tenant_id'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}
