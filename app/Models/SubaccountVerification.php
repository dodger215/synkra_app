<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class SubaccountVerification extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'subaccount_id', 'verification_status', 'failure_reason', 
        'bank_response', 'attempted_by', 'attempted_at', 'verified_at'
    ];

    protected $casts = [
        'bank_response' => 'array',
        'attempted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function subaccount() { return $this->belongsTo(TenantSubaccount::class, 'subaccount_id'); }
    public function attempter() { return $this->belongsTo(User::class, 'attempted_by'); }
}
