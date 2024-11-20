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
        'notified',
        'type',
        'sub_type',
        'activity_type',
        'pinned',
        'user_id',
    ];

    public function chattable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get pinned chats.
     */
    public function scopePinned($query)
    {
        return $query->where('pinned', true);
    }

    /**
     * Pin a chat message.
     */
    public function pin()
    {
        $this->update(['pinned' => true]);
    }

    /**
     * Unpin a chat message.
     */
    public function unpin()
    {
        $this->update(['pinned' => false]);
    }
}
