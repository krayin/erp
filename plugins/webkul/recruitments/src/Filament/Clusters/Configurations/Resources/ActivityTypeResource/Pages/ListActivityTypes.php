<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\ListActivityTypes as BaseListActivityTypes;

class ListActivityTypes extends BaseListActivityTypes
{
    protected static string $resource = ActivityTypeResource::class;

    protected static ?string $pluginName = 'recruitments';
}
