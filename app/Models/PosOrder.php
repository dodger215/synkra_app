<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class PosOrder extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'pos_session_id', 'order_number', 'customer_id', 'pos_table_id',
        'order_type', 'status', 'subtotal', 'discount_amount', 'tax_amount', 'total_amount',
        'paid_amount', 'change_amount', 'payment_status', 'payment_method', 'notes', 'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2', 'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2', 'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2', 'change_amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function session() { return $this->belongsTo(PosSession::class, 'pos_session_id'); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function table() { return $this->belongsTo(PosTable::class, 'pos_table_id'); }
    public function items() { return $this->hasMany(PosOrderItem::class, 'pos_order_id'); }
}
