<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource;

class CreateCalendar extends CreateRecord
{
    protected static string $resource = CalendarResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
