<?php

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Resources\TaskResource;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('projects::app.filament.resources.task.pages.create.notification.title'))
            ->body(__('projects::app.filament.resources.task.pages.create.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();

        return $data;
    }
}
