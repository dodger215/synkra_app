<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class SupplierContract extends Model
{
    use HasUuidV5;

    protected $fillable = ['supplier_id', 'contract_number', 'start_date', 'end_date', 'terms', 'file_url', 'is_active'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'terms' => 'array', 'is_active' => 'boolean'];

    public function supplier() { return $this->belongsTo(Supplier::class); }
}
