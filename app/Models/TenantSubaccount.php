<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class TenantSubaccount extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'bank_code', 'bank_name', 'account_number', 'account_name',
        'percentage_charge', 'settlement_bank', 'currency', 'subaccount_code',
        'paystack_subaccount_id', 'is_verified', 'is_active', 'settlement_schedule',
        'metadata', 'vendor_id', 'store_id', 'verified_at'
    ];

    protected $casts = [
        'percentage_charge' => 'decimal:2',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array',
        'verified_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function store() { return $this->belongsTo(EcommerceStore::class, 'store_id'); }
    public function verifications() { return $this->hasMany(SubaccountVerification::class, 'subaccount_id'); }
    public function transactions() { return $this->hasMany(SubaccountTransaction::class, 'subaccount_id'); }
    public function payouts() { return $this->hasMany(TenantPayout::class, 'subaccount_id'); }
}
