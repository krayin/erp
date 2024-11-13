<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\User;

class Note extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function noteable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}