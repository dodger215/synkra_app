<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class DemandForecast extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'product_id', 'forecast_date', 'forecast_quantity', 'confidence_lower',
        'confidence_upper', 'model_version', 'factors',
    ];

    protected $casts = ['forecast_date' => 'date', 'factors' => 'array'];

    public function product() { return $this->belongsTo(Product::class); }
}
