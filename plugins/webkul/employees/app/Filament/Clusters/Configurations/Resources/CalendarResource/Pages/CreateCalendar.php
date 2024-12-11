<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;

use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCalendar extends CreateRecord
{
    protected static string $resource = CalendarResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
