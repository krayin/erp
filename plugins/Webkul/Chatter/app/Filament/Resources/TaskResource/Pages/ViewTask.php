<?php

namespace Webkul\Chatter\Filament\Resources\TaskResource\Pages;

use Webkul\Chatter\Filament\Resources\TaskResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make(),
            EditAction::make(),
        ];
    }
}
