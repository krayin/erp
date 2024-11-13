<?php 

namespace Webkul\Chatter\Events;

use Webkul\Chatter\Models\Message;

class MessagePosted
{
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}