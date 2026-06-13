<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class StockCountSchedule extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'name', 'location_id', 'count_type', 'frequency',
        'next_count_date', 'assigned_to', 'is_active',
    ];

    protected $casts = [
        'next_count_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function location() { return $this->belongsTo(StockLocation::class, 'location_id'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function counts() { return $this->hasMany(StockCount::class, 'schedule_id'); }
}
