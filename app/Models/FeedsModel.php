<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class FeedsModel extends Model
{
    use HasUuidV5;


    protected $table = 'feeds';

    protected $fillable = [
        'tenant_id', 'followed_entities', 'unfollowed_entities',
        'liked_entities', 'unliked_entities', 'shared_entities',
        'commented_entities',
    ];


    protected $casts = [
        'followed_entities' => 'array',
        'unfollowed_entities' => 'array',
        'liked_entities' => 'array',
        'unliked_entities' => 'array',
        'shared_entities' => 'array',
        'commented_entities' => 'array',
    ];


    public function tenant() { return $this->belongsTo(Tenant::class); }
}
