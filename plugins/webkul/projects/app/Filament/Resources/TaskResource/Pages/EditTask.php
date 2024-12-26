<?php

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Project\Filament\Resources\TaskResource;
use Filament\Notifications\Notification;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Task updated')
            ->body('The task has been saved successfully.');
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Task deleted')
                        ->body('The task has been deleted successfully.'),
                ),
        ];
    }
}
