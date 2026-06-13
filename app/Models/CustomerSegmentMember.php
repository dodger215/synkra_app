<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CustomerSegmentMember extends Pivot
{
    protected $table = 'customer_segment_members';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = ['added_at' => 'datetime'];
}
