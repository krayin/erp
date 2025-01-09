<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

class UTMMedium extends Model
{
    protected $table = 'recruitments_utm_mediums';

    protected $fillable = ['name', 'creator_id', 'is_active'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
