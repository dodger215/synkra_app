<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class TransferBatch extends Model
{
    use HasUuidV5;

    protected $fillable = ['transfer_id', 'batch_number', 'quantity', 'expiry_date'];

    protected $casts = ['expiry_date' => 'date'];

    public function transfer() { return $this->belongsTo(StockTransfer::class, 'transfer_id'); }
}
