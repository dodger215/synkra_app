<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockAdjustmentReason extends Model
{
    use HasUuidV5;

    protected $fillable = ['tenant_id', 'reason_code', 'reason_name', 'adjustment_type', 'category'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function adjustments() { return $this->hasMany(StockAdjustment::class, 'reason_id'); }
}
