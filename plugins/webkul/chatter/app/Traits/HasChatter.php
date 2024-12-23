<?php

namespace Webkul\Chatter\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Collection;
use Webkul\Chatter\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait HasChatter
{
    /**
     * Get all messages for this model
     */
    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable')->orderBy('created_at', 'desc');
    }

    /**
     * Add a new message
     */
    public function addMessage(array $data): Message
    {
        $message = new Message();

        $message->fill(array_merge($data, [
            'creator_id' => Auth::user()->id,
            'date' => $data['date'] ?? now(),
            'company_id' => $data['company_id'] ?? $this->company_id ?? null,
        ]));

        $this->messages()->save($message);

        return $message;
    }

    /**
     * Add a reply to an existing message
     */
    public function replyToMessage(Message $parentMessage, array $data): Message
    {
        return $this->addMessage(array_merge($data, [
            'parent_id' => $parentMessage->id,
            'company_id' => $parentMessage->company_id,
            'activity_type_id' => $parentMessage->activity_type_id,
        ]));
    }

    /**
     * Remove a message
     */
    public function removeMessage($messageId): bool
    {
        $message = $this->messages()->find($messageId);

        if (
            $message->messageable_id !== $this->id
            || $message->messageable_type !== get_class($this)
        ) {
            return false;
        }

        return $message->delete();
    }

    /**
     * Pin a message
     */
    public function pinMessage(Message $message): bool
    {
        if (
            $message->messageable_id !== $this->id
            || $message->messageable_type !== get_class($this)
        ) {
            return false;
        }

        $message->pinned_at = now();
        return $message->save();
    }

    /**
     * Unpin a message
     */
    public function unpinMessage(Message $message): bool
    {
        if (
            $message->messageable_id !== $this->id
            || $message->messageable_type !== get_class($this)
        ) {
            return false;
        }

        $message->pinned_at = null;

        return $message->save();
    }

    /**
     * Get all pinned messages
     */
    public function getPinnedMessages(): Collection
    {
        return $this->messages()->whereNotNull('pinned_at')->orderBy('pinned_at', 'desc')->get();
    }

    /**
     * Get messages by type
     */
    public function getMessagesByType(string $type): Collection
    {
        return $this->messages()->where('type', $type)->get();
    }

    /**
     * Get internal messages
     */
    public function getInternalMessages(): Collection
    {
        return $this->messages()->where('is_internal', true)->get();
    }

    /**
     * Get messages by date range
     */
    public function getMessagesByDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->messages()
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    }

    /**
     * Get messages by activity type
     */
    public function getMessagesByActivityType(int $activityTypeId): Collection
    {
        return $this->messages()
            ->where('activity_type_id', $activityTypeId)
            ->get();
    }

    /**
     * Get thread messages (messages with same parent)
     */
    public function getThreadMessages(Message $message): Collection
    {
        $parentId = $message->parent_id ?? $message->id;

        return $this->messages()
            ->where(function ($query) use ($parentId) {
                $query->where('id', $parentId)
                    ->orWhere('parent_id', $parentId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
