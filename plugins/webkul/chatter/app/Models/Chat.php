<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Security\Models\User;

class Chat extends Model
{
    protected $fillable = [
        'chattable_type',
        'chattable_id',
        'type',
        'activity_type',
        'content',
        'changes',
        'user_id',
        'assigned_to',
        'due_date',
        'summary',
    ];

    protected $casts = [
        'changes'  => 'array',
        'due_date' => 'date',
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
