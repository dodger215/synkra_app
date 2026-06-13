<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class CustomerAddress extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'customer_id', 'address_type', 'address_line1', 'address_line2',
        'city', 'state', 'postal_code', 'country', 'is_default',
    ];

    protected $casts = ['is_default' => 'boolean'];

    public function customer() { return $this->belongsTo(Customer::class); }
}
