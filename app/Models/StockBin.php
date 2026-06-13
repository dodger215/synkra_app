<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockBin extends Model
{
    use HasUuidV5;

    protected $fillable = ['location_id', 'bin_code', 'bin_type', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
}
