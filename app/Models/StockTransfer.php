<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockTransfer extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'transfer_number', 'product_id', 'from_location_id',
        'to_location_id', 'quantity', 'status', 'requested_by', 'requested_at',
        'shipped_by', 'shipped_at', 'received_by', 'received_at',
        'tracking_number', 'notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'shipped_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function fromLocation() { return $this->belongsTo(StockLocation::class, 'from_location_id'); }
    public function toLocation() { return $this->belongsTo(StockLocation::class, 'to_location_id'); }
    public function requester() { return $this->belongsTo(User::class, 'requested_by'); }
    public function shipper() { return $this->belongsTo(User::class, 'shipped_by'); }
    public function receiver() { return $this->belongsTo(User::class, 'received_by'); }
    public function batches() { return $this->hasMany(TransferBatch::class, 'transfer_id'); }
}
