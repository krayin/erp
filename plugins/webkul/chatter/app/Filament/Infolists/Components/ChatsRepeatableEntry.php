<?php

namespace Webkul\Chatter\Filament\Infolists\Components;

use Filament\Infolists\Components\RepeatableEntry;

class ChatsRepeatableEntry extends RepeatableEntry
{
    protected function setup(): void
    {
        parent::setup();

        $this->configureRepeatableEntry();
    }

    private function configureRepeatableEntry(): void
    {
        $this
            ->contained(false)
            ->hiddenLabel();
    }

    protected string $view = 'chatter::filament.infolists.components.messages.chats-repeatable-entry';
}
