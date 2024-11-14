<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMessage extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id'];
}
