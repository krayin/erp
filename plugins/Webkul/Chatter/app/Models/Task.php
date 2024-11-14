<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function followers()
    {
        return $this->belongsToMany(User::class, 'task_followers');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
