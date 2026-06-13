<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class Supplier extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'supplier_code', 'company_name', 'contact_person', 'email',
        'phone', 'address', 'tax_number', 'payment_terms', 'lead_time_days',
        'min_order_amount', 'rating', 'is_preferred', 'is_active',
    ];

    protected $casts = [
        'min_order_amount' => 'decimal:2', 'rating' => 'decimal:2',
        'is_preferred' => 'boolean', 'is_active' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
    public function contracts() { return $this->hasMany(SupplierContract::class); }
}
