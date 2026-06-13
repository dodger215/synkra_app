<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class EcommerceStore extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'tenant_id', 'store_name', 'domain', 'logo_url', 'favicon_url',
        'primary_color', 'secondary_color', 'theme_id', 'currency',
        'language', 'is_published', 'seo_settings', 'social_links',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'seo_settings' => 'array',
        'social_links' => 'array',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function pages() { return $this->hasMany(EcommercePage::class, 'store_id'); }
    public function orders() { return $this->hasMany(EcommerceOrder::class, 'store_id'); }
    public function subaccounts() { return $this->hasMany(TenantSubaccount::class, 'store_id'); }
}
