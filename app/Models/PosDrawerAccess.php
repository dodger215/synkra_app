<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidV5;

class PosDrawerAccess extends Model
{
    use HasUuidV5;

    protected $fillable = ['tenant_id', 'pos_session_id', 'pos_device_id', 'user_id', 'reason'];

    public function user() { return $this->belongsTo(User::class); }
    public function device() { return $this->belongsTo(PosDevice::class, 'pos_device_id'); }
    public function session() { return $this->belongsTo(PosSession::class, 'pos_session_id'); }
}
