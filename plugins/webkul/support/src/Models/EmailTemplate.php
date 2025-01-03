<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'subject',
        'content',
        'description',
        'is_active',
        'sender_name',

    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];
}
