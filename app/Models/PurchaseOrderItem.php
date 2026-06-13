<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class PurchaseOrderItem extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'po_id', 'product_id', 'quantity_ordered', 'quantity_received',
        'unit_cost', 'total_cost', 'discount_percent', 'received_by',
        'last_received_at', 'notes',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2', 'total_cost' => 'decimal:2',
        'discount_percent' => 'decimal:2', 'last_received_at' => 'datetime',
    ];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class, 'po_id'); }
    public function product() { return $this->belongsTo(Product::class); }
    public function receiver() { return $this->belongsTo(User::class, 'received_by'); }
}
