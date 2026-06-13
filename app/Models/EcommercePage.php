<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class EcommercePage extends Model
{
    use HasUuidV5;

    protected $fillable = [
        'store_id', 'page_name', 'slug', 'page_type', 'title',
        'meta_description', 'content', 'is_published', 'publish_date',
        'sort_order', 'created_by',
    ];

    protected $casts = [
        'content' => 'array',
        'is_published' => 'boolean',
        'publish_date' => 'datetime',
    ];

    public function store() { return $this->belongsTo(EcommerceStore::class, 'store_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
