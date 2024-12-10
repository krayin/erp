<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\ActivityPlanResource\Pages;

use Webkul\Employee\Filament\Clusters\Employee\Resources\ActivityPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityPlans extends ListRecords
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus-circle'),
        ];
    }
}
