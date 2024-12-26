<?php

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Project\Filament\Resources\TaskResource;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('projects::app.filament.resources.task.pages.view.header-actions.delete.notification.title'))
                        ->body(__('projects::app.filament.resources.task.pages.view.header-actions.delete.notification.body')),
                ),
        ];
    }
}
