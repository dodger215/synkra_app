<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class CustomerInteraction extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'customer_id', 'interaction_type', 'channel', 'subject',
        'content', 'created_by', 'follow_up_date', 'resolved_at',
    ];

    protected $casts = ['follow_up_date' => 'date', 'resolved_at' => 'datetime'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
