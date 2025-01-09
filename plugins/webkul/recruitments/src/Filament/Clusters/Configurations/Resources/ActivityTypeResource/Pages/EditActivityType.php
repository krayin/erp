<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\EditActivityType as BaseEditActivityType;

class EditActivityType extends BaseEditActivityType
{
    protected static string $resource = ActivityTypeResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['plugin'] = 'recruitments';

        return $data;
    }
}
