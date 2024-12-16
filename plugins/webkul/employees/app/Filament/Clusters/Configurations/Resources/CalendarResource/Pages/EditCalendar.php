<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource;

class EditCalendar extends EditRecord
{
    protected static string $resource = CalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function afterSave(): void
    {
        // $this->dispatch('refreshCalendarResource');
    }
}
