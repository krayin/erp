<?php

namespace Webkul\Chatter\Filament\Widgets;

use Filament\Widgets\Widget;

class ChatterWidget extends Widget
{
    protected static string $view = 'chatter::filament.widgets.chatter';

    // Set this to true to make it span the full width
    protected int|string|array $columnSpan = 'full';

    // Make it appear in the footer
    protected static string $type = 'footer';

    public static function canView(): bool
    {
        return false; // This will hide the widget completely
    }
}
