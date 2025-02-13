<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\ListActivityTypes as BaseListActivityTypes;

class ListActivityTypes extends BaseListActivityTypes
{
    protected static string $resource = ActivityTypeResource::class;
}
