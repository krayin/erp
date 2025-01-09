<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\CreateActivityType as BaseCreateActivityType;

class CreateActivityType extends BaseCreateActivityType
{
    protected static string $resource = ActivityTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['plugin'] = 'recruitments';

        return $data;
    }
}
