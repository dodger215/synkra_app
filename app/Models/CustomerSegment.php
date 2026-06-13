<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class CustomerSegment extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'segment_name', 'segment_type', 'conditions',
        'member_count', 'created_by', 'last_updated_at',
    ];

    protected $casts = ['conditions' => 'array', 'last_updated_at' => 'datetime'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function customers() { return $this->belongsToMany(Customer::class, 'customer_segment_members', 'segment_id', 'customer_id'); }
}
