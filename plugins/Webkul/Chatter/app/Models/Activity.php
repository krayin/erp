<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\User;

class Activity extends Model
{
    protected $fillable = ['type', 'due_date', 'user_id'];

    public function activityable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}