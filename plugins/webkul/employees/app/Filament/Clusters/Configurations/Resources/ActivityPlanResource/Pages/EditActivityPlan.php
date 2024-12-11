<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource;

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
