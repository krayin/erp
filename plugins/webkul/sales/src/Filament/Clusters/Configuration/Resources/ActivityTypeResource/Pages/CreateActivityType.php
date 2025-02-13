<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\CreateActivityType as BaseCreateActivityType;

class CreateActivityType extends BaseCreateActivityType
{
    protected static string $resource = ActivityTypeResource::class;
}
