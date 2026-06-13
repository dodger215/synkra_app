<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockMovementType extends Model
{
    use HasUuidV5;

    protected $fillable = ['name', 'movement_direction', 'affects_balance'];

    protected $casts = ['affects_balance' => 'boolean'];

    public function movements() { return $this->hasMany(StockMovement::class, 'movement_type_id'); }
}
