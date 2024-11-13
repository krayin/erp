<?php

namespace Webkul\Chatter\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new message was posted.')
                    ->action('View Message', url('/'))
                    ->line('Thank you for using our application!');
    }
}