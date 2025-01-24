<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\PublicHolidayResource\Pages;

use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\PublicHolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublicHolidays extends ListRecords
{
    protected static string $resource = PublicHolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
