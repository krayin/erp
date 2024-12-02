<?php

namespace Webkul\Chatter\Filament\Widgets;

use Filament\Widgets\Widget;

class ChatterWidget extends Widget
{
    protected static string $view = 'chatter::filament.widgets.chatter';

    // Set this to true to make it span the full width
    protected int|string|array $columnSpan = 'full';
    
    // Make the record a public property that can be set
    public $record = null;

    // Make it appear in the footer
    protected static string $type = 'footer';

    // Add a method to set the record
    public function mount($record = null)
    {
        $this->record = $record;
    }

    public static function canView(): bool
    {
        return true; // Enable the widget
    }

    // Optional: Add a method to get the record in your view
    public function getRecord()
    {
        return $this->record;
    }
}
