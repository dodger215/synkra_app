<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class TenantPayout extends Model
{
    use HasUuidV5;

    protected $table = 'tenant_payout';

    protected $fillable = [
        'subaccount_id', 'amount'
    ];

    public function subaccount() { return $this->belongsTo(TenantSubaccount::class); }
}
