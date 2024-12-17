<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityTypeResource;

class CreateActivityType extends CreateRecord
{
    protected static string $resource = ActivityTypeResource::class;
}
