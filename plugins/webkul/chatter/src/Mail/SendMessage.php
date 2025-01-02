<?php

namespace Webkul\Chatter\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\Chatter\Models\Message;

class SendMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public mixed $record,
        public mixed $follower,
        public Message $message
    ) {
        $this->subject = __('chatter::mail/send-message.subject', [
            'app' => config('app.name'),
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
            to: [
                new Address(
                    $this->follower->email,
                    $this->follower->name
                ),
            ],
            replyTo: [
                new Address(config('mail.from.address'), config('mail.from.name')),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'chatter::emails.send-message',
            with: [
                'messageBody' => $this->message->body,
                'follower' => $this->follower,
                'record' => $this->record,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
