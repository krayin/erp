<?php

namespace Webkul\Chatter\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webkul\Chatter\Models\Chat;
use Webkul\Security\Models\User;

trait HasChatter
{
    public function chats(): MorphMany
    {
        return $this->morphMany(Chat::class, 'chattable')
            ->latest();
    }

    public function followers(): MorphToMany
    {
        return $this->morphToMany(User::class, 'followable', 'followers')
            ->withTimestamps()
            ->withPivot('followed_at')
            ->select('users.*');
    }

    public function addChat($data, $userId): Model
    {
        return $this->chats()->create([
            ...$data,
            'user_id' => $userId,
        ]);
    }

    public function removeChat($chatId): bool
    {
        return $this->chats()->where('id', $chatId)->delete();
    }

    public function isFollowedBy($userId): bool
    {
        return $this->followers()->where('user_id', $userId)->exists();
    }

    public function addFollower($userId): void
    {
        if (! $this->isFollowedBy($userId)) {
            $this->followers()->attach($userId, ['followed_at' => now()]);
        }
    }

    public function removeFollower($userId): void
    {
        $this->followers()->detach($userId);
    }

    public function getFollowerIds(): array
    {
        return $this->followers()->pluck('id')->toArray();
    }

    public function canSendMessage(Model $followable): bool
    {
        return $followable->followers()
            ->where('user_id', $this->id)
            ->exists();
    }
}
