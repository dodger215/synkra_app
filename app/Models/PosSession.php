<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class PosSession extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'pos_device_id', 'cashier_id', 'started_at', 'ended_at',
        'opening_balance', 'closing_balance', 'cash_sales', 'card_sales',
        'expected_cash', 'actual_cash', 'variance', 'status',
    ];

    protected $casts = [
        'started_at' => 'datetime', 'ended_at' => 'datetime',
        'opening_balance' => 'decimal:2', 'closing_balance' => 'decimal:2',
        'cash_sales' => 'decimal:2', 'card_sales' => 'decimal:2',
        'expected_cash' => 'decimal:2', 'actual_cash' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function device() { return $this->belongsTo(PosDevice::class, 'pos_device_id'); }
    public function cashier() { return $this->belongsTo(User::class, 'cashier_id'); }
    public function orders() { return $this->hasMany(PosOrder::class, 'pos_session_id'); }
}
