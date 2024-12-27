<?php

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Project\Filament\Resources\TaskResource;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('projects::filament/resources/task/pages/edit-task.notification.title'))
            ->body(__('projects::filament/resources/task/pages/edit-task.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('projects::filament/resources/task/pages/edit-task.header-actions.delete.notification.title'))
                        ->body(__('projects::filament/resources/task/pages/edit-task.header-actions.delete.notification.body')),
                ),
        ];
    }
}
