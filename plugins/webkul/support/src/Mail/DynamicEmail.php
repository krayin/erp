<?php

namespace Webkul\Support\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\HtmlString;
use Webkul\Security\Models\User;

class DynamicEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The email data containing template parts
     */
    protected array $emailData;

    /**
     * Custom CSS for email styling
     */
    protected ?string $customCss;

    /**
     * Attachments for the email
     */
    protected array $attachmentData = [];

    /**
     * Get the sender details
     */
    protected User $sender;

    /**
     * Create a new message instance.
     */
    public function __construct(array $emailData, User $sender)
    {
        $this->emailData = $emailData;
        $this->customCss = $emailData['css'] ?? null;
        $this->sender = $sender;
    }

    /**
     * Add attachments to the email
     */
    public function withAttachments(array $attachments): self
    {
        $this->attachmentData = $attachments;
        return $this;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $mail = $this->subject($this->emailData['subject'])
            ->view('support::emails.dynamic-template')
            ->with([
                'header' => html_entity_decode($this->emailData['header'] ?? ''),
                'body' => html_entity_decode($this->emailData['body']),
                'footer' => html_entity_decode($this->emailData['footer'] ?? ''),
                'sender' => $this->sender,
            ]);

        if (isset($this->emailData['from'])) {
            $mail->from(
                $this->emailData['from']['address'],
                $this->emailData['from']['name'] ?? null
            );
        }

        foreach ($this->attachmentData as $attachment) {
            if (isset($attachment['path'])) {
                $mail->attach($attachment['path'], [
                    'as' => $attachment['name'] ?? null,
                    'mime' => $attachment['mime'] ?? null,
                ]);
            } elseif (isset($attachment['data'])) {
                $mail->attachData($attachment['data'], $attachment['name'], [
                    'mime' => $attachment['mime'] ?? null,
                ]);
            }
        }

        return $mail;
    }
}
