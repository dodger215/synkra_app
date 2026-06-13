<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class LoyaltyTransaction extends Model
{
    use HasUuidV5;

    protected $fillable = ['customer_id', 'program_id', 'transaction_type', 'points', 'reference_type', 'reference_id'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function program() { return $this->belongsTo(LoyaltyProgram::class, 'program_id'); }
}
