<?php

namespace Webkul\Chatter\Filament\Infolists\Components\Messages;

use Filament\Forms\Components\Concerns\CanAllowHtml;
use Filament\Infolists\Components\Entry;
use Filament\Support\Concerns\HasExtraAttributes;

class ContentTextEntry extends Entry
{
    use CanAllowHtml;
    use HasExtraAttributes;

    protected string $view = 'chatter::filament.infolists.components.messages.content-text-entry';

    protected function setUp(): void
    {
        parent::setUp();
    }
}
