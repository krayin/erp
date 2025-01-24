<?php

namespace Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource\Pages;

use Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListByEmployees extends ListRecords
{
    protected static string $resource = ByEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
