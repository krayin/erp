<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\User;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
