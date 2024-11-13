<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\User;

class Follower extends Model
{
    protected $fillable = ['user_id'];

    public function followable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}