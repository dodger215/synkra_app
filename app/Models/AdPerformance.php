<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class AdPerformance extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'ad_set_id', 'date', 'impressions', 'clicks', 'ctr', 'spend',
        'conversions', 'conversion_value', 'roas', 'cost_per_conversion',
        'frequency', 'reach',
    ];

    protected $casts = [
        'date' => 'date', 'ctr' => 'decimal:4', 'spend' => 'decimal:2',
        'conversion_value' => 'decimal:2', 'roas' => 'decimal:4',
        'cost_per_conversion' => 'decimal:2', 'frequency' => 'decimal:2',
    ];

    public function adSet() { return $this->belongsTo(AdSet::class, 'ad_set_id'); }
}
