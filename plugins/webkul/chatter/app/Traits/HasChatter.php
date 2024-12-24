<?php

namespace Webkul\Chatter\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webkul\Chatter\Models\Attachment;
use Webkul\Chatter\Models\Message;

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

    public function activities()
    {
        return $this->morphMany(Message::class, 'messageable')
            ->where('type', 'activity')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Add a new message
     */
    public function addMessage(array $data): Message
    {
        $message = new Message;

        $message->fill(array_merge($data, [
            'creator_id' => Auth::user()->id,
            'date_deadline' => $data['date_deadline'] ?? now(),
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
            'parent_id'        => $parentMessage->id,
            'company_id'       => $parentMessage->company_id,
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

    /**
     * Get all attachments for this model
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'messageable')->orderBy('created_at', 'desc');
    }

    /**
     * Add a new attachment
     */
    public function addAttachment(array|UploadedFile $file, array $additionalData = []): Attachment
    {
        if ($file instanceof UploadedFile) {
            $path = $file->store('chats-attachments', 'public');
            $fileData = [
                'file_path'          => $path,
                'original_file_name' => $file->getClientOriginalName(),
                'mime_type'          => $file->getMimeType(),
                'file_size'          => $file->getSize(),
            ];
        } else {
            $fileData = $file;
        }

        $attachment = new Attachment;
        $attachment->fill(array_merge($fileData, $additionalData, [
            'creator_id' => Auth::user()->id,
            'company_id' => $additionalData['company_id'] ?? $this->company_id ?? null,
        ]));

        $this->attachments()->save($attachment);

        return $attachment;
    }

    /**
     * Add multiple attachments
     */
    public function addAttachments(array $files, array $additionalData = []): Collection
    {
        $attachments = collect($files)->map(function ($file) use ($additionalData) {
            return $this->addAttachment($file, $additionalData);
        });

        return new Collection($attachments);
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

        // Delete the physical file
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
}
