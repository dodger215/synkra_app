<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class ReceivingReport extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'po_id', 'receiving_number', 'received_by', 'received_at',
        'location_id', 'quality_check_passed', 'notes', 'status',
    ];

    protected $casts = ['received_at' => 'datetime', 'quality_check_passed' => 'boolean'];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class, 'po_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'received_by'); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
}
