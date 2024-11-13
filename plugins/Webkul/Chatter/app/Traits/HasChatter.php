<?php

namespace Webkul\Chatter\Traits;

use App\Models\Message;
use App\Models\Note;
use App\Models\Activity;
use App\Models\Follower;

trait HasChatter
{
    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    public function followers()
    {
        return $this->morphMany(Follower::class, 'followable');
    }

    public function addMessage($userId, $content)
    {
        return $this->messages()->create(['user_id' => $userId, 'content' => $content]);
    }

    public function addNote($userId, $content)
    {
        return $this->notes()->create(['user_id' => $userId, 'content' => $content]);
    }

    public function addActivity($userId, $type, $dueDate = null)
    {
        return $this->activities()->create(['user_id' => $userId, 'type' => $type, 'due_date' => $dueDate]);
    }

    public function addFollower($userId)
    {
        return $this->followers()->create(['user_id' => $userId]);
    }
}
