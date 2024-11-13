<?php

namespace Webkul\Chatter\Filament\Widgets;

use Filament\Widgets\Widget;
use Webkul\Chatter\Models\Message;

class RecentMessagesWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-messages-widget';

    protected function getData(): array
    {
        return [
            'messages' => Message::latest()->take(10)->get(),
        ];
    }
}
