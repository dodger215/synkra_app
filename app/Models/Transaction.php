<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Transaction extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'transaction_reference', 'reference_type', 'reference_id', 
        'amount', 'currency', 'payment_method', 'payment_gateway', 'status', 
        'gateway_response', 'customer_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function refunds() { return $this->hasMany(Refund::class, 'transaction_id'); }
}
