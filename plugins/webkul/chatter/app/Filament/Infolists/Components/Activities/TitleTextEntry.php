<?php

namespace Webkul\Chatter\Filament\Infolists\Components\Activities;

use Filament\Forms\Components\Concerns\CanAllowHtml;
use Filament\Infolists\Components\Entry;
use Filament\Support\Concerns\HasExtraAttributes;

class TitleTextEntry extends Entry
{
    use CanAllowHtml;
    use HasExtraAttributes;

    protected string $view = 'chatter::filament.infolists.components.activities.title-text-entry';

    protected function setUp(): void
    {
        parent::setUp();
    }
}
