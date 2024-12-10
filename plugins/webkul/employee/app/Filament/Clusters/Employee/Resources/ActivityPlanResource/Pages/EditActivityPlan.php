<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\ActivityPlanResource\Pages;

use Webkul\Employee\Filament\Clusters\Employee\Resources\ActivityPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivityPlan extends EditRecord
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
