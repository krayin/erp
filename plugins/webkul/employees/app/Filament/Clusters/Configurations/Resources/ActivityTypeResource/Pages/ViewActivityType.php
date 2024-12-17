<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityTypeResource;

class ViewActivityType extends ViewRecord
{
    protected static string $resource = ActivityTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
