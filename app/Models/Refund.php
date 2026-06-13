<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Refund extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'transaction_id', 'refund_reference', 'amount', 
        'currency', 'reason', 'status', 'gateway_response', 'processed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function processor() { return $this->belongsTo(User::class, 'processed_by'); }
}
