<?php

namespace Webkul\Chatter\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webkul\Chatter\Models\Attachment;
use Webkul\Chatter\Models\Follower;
use Webkul\Chatter\Models\Message;
use Webkul\Security\Models\User;

trait HasChatter
{
    /**
     * Get all messages for this model
     */
    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable')
            ->whereNot('type', 'activity')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all activity messages for this model
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable')
            ->where('type', 'activity')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all activity plans for this model
     */
    public function activityPlans(): mixed
    {
        return collect();
    }

    /**
     * Add a new message
     */
    public function addMessage(array $data): Message
    {
        $message = new Message;

        $user = Auth::user();

        $message->fill(array_merge($data, [
            'creator_id'    => $user->id,
            'date_deadline' => $data['date_deadline'] ?? now(),
            'company_id'    => $data['company_id'] ?? ($user->defaultCompany?->id ?? null),
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
            'parent_id'        => $parentMessage->id,
            'company_id'       => $parentMessage->company_id,
            'activity_type_id' => $parentMessage->activity_type_id,
        ]));
    }

    /**
     * Remove a message
     */
    public function removeMessage($messageId, $type = 'messages'): bool
    {
        $message = $this->{$type}()->find($messageId);

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
     * Get all attachments for this model
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'messageable')->orderBy('created_at', 'desc');
    }

    /**
     * Add multiple attachments
     */
    public function addAttachments(array $files, array $additionalData = []): Collection
    {
        if (empty($files)) {
            return collect();
        }

        return $this->attachments()
            ->createMany(
                collect($files)
                    ->map(fn($filePath) => [
                        'file_path'          => $filePath,
                        'original_file_name' => basename($filePath),
                        'mime_type'          => mime_content_type($storagePath = storage_path('app/public/' . $filePath)) ?: 'application/octet-stream',
                        'file_size'          => filesize($storagePath) ?: 0,
                        'creator_id'         => Auth::id(),
                        ...$additionalData,
                    ])
                    ->filter()
                    ->toArray()
            );
    }

    /**
     * Remove an attachment
     */
    public function removeAttachment($attachmentId): bool
    {
        $attachment = $this->attachments()->find($attachmentId);

        if (
            ! $attachment ||
            $attachment->messageable_id !== $this->id ||
            $attachment->messageable_type !== get_class($this)
        ) {
            return false;
        }

        if (Storage::exists('public/' . $attachment->file_path)) {
            Storage::delete('public/' . $attachment->file_path);
        }

        return $attachment->delete();
    }

    /**
     * Get attachments by type
     */
    public function getAttachmentsByType(string $mimeType): Collection
    {
        return $this->attachments()
            ->where('mime_type', 'LIKE', $mimeType . '%')
            ->get();
    }

    /**
     * Get attachments by date range
     */
    public function getAttachmentsByDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->attachments()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    /**
     * Get all image attachments
     */
    public function getImageAttachments(): Collection
    {
        return $this->getAttachmentsByType('image/');
    }

    /**
     * Get all document attachments
     */
    public function getDocumentAttachments(): Collection
    {
        return $this->attachments()
            ->where('mime_type', 'NOT LIKE', 'image/%')
            ->get();
    }

    /**
     * Check if file exists
     */
    public function attachmentExists($attachmentId): bool
    {
        $attachment = $this->attachments()->find($attachmentId);

        return $attachment && Storage::exists('public/' . $attachment->file_path);
    }

    /*
    * Get all followers for this model
    */
    public function followers(): MorphMany
    {
        return $this->morphMany(Follower::class, 'followable');
    }

    /**
     * Get all non followers
     */
    public function nonFollowers(): Collection
    {
        return User::whereNotIn('id', $this->followers()->pluck('user_id'))->get();
    }

    /**
     * Add a follower to this model
     */
    public function addFollower(User $user): Follower
    {
        $follower = $this->followers()->firstOrNew([
            'user_id' => $user->id,
        ]);

        if (!$follower->exists) {
            $follower->followed_at = now();
            $follower->save();
        }

        return $follower;
    }

    /**
     * Remove a follower from this model
     */
    public function removeFollower(User $user): bool
    {
        return (bool) $this->followers()
            ->where('user_id', $user->id)
            ->delete();
    }

    /**
     * Check if a user is following this model
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get all active followers
     */
    public function getActiveFollowers(): Collection
    {
        return $this->followers()
            ->whereNotNull('followed_at')
            ->with('user')
            ->get();
    }
}
