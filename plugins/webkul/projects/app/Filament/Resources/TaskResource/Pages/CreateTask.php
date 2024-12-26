<?php

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Resources\TaskResource;
use Filament\Notifications\Notification;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Task created')
            ->body('The task has been created successfully.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();

        return $data;
    }
}
