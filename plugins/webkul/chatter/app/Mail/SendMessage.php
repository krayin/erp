<?php

namespace Webkul\Chatter\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\Chatter\Models\Chat;
use Webkul\Support\Models\User;
use Illuminate\Mail\Mailables\Address;

class SendMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public mixed $record,
        public User $follower,
        public Chat $chat
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->record->user->email, $this->record->user->name),
            to: [
                new Address(
                    $this->follower->email,
                    $this->follower->name
                )
            ],
            subject: 'New Message from ' . $this->record->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'chatter::emails.send-message',
            with: [
                'content'    => $this->chat->content,
                'senderName' => $this->record->user->name,
            ],
        );
    }
}
