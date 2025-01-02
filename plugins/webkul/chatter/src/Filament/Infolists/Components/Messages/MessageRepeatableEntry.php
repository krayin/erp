<?php

namespace Webkul\Chatter\Filament\Infolists\Components\Messages;

use Filament\Infolists\Components\RepeatableEntry;

class MessageRepeatableEntry extends RepeatableEntry
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

    protected string $view = 'chatter::filament.infolists.components.messages.repeatable-entry';
}
