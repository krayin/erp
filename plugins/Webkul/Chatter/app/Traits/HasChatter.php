<?php

namespace Webkul\Chatter\Traits;

use Webkul\Chatter\Models\Chat;

trait HasChatter
{
    public function chats()
    {
        return $this->morphMany(Chat::class, 'chattable');
    }

    public function addChat($message, $userId)
    {
        return $this->chats()->create([
            'message' => $message,
            'user_id' => $userId
        ]);
    }

    public function removeChat($chatId)
    {
        return $this->chats()->where('id', $chatId)->delete();
    }

    public function getLatestChats($limit = 10)
    {
        return $this->chats()
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }
}