<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\ActivityTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\CreateActivityType as BaseCreateActivityType;

class CreateActivityType extends BaseCreateActivityType
{
    protected static string $resource = ActivityTypeResource::class;
}
