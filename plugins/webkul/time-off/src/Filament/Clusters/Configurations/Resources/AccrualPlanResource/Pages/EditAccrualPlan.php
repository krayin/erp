<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\Pages;

use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccrualPlan extends EditRecord
{
    protected static string $resource = AccrualPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
