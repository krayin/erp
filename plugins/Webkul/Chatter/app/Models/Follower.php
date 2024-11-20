<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Core\Models\User;

class Follower extends Model
{
    protected $fillable = [
        'followable_id',
        'followable_type',
        'user_id'
    ];

    protected $casts = [
        'followed_at' => 'datetime'
    ];

    public function followable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
