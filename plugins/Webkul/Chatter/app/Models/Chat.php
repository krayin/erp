<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Core\Models\User;

class Chat extends Model
{
    protected $fillable = [
        'content',
        'type',
        'activity_type',
        'pinned',
        'user_id',
        'due_date',
        'summary',
        'assigned_to',
    ];

    public function chattable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachments()
    {
        return $this->hasMany(ChatAttachment::class);
    }
}
