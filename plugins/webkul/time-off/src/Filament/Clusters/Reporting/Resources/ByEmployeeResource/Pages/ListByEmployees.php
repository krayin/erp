<?php

namespace Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource\Pages;

use Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\Pages\ListTimeOffs;

class ListByEmployees extends ListTimeOffs
{
    protected static string $resource = ByEmployeeResource::class;
}
