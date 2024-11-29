<?php

namespace Webkul\Chatter\Filament\Resources\TaskResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Chatter\Filament\Resources\TaskResource;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make(),
            Actions\EditAction::make(),
        ];
    }
}
