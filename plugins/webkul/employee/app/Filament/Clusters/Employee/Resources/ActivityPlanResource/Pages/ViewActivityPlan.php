<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\ActivityPlanResource\Pages;

use Webkul\Employee\Filament\Clusters\Employee\Resources\ActivityPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewActivityPlan extends ViewRecord
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
