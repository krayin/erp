<?php

namespace Webkul\Chatter\Filament\Infolists\Components\Activities;

use Filament\Infolists\Components\RepeatableEntry;

class ActivitiesRepeatableEntry extends RepeatableEntry
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

    protected string $view = 'chatter::filament.infolists.components.activities.repeatable-entry';
}
