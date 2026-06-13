<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class SubaccountTransaction extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'subaccount_id', 'transaction_reference', 'amount', 'fee_charged', 
        'net_amount', 'status', 'paystack_transfer_code', 'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_charged' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function subaccount() { return $this->belongsTo(TenantSubaccount::class, 'subaccount_id'); }
}
