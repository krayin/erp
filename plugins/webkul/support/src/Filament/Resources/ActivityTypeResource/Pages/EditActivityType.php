<?php

namespace Webkul\Support\Filament\Resources\ActivityTypeResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Support\Filament\Resources\ActivityTypeResource;

class EditActivityType extends EditRecord
{
    protected static string $resource = ActivityTypeResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('support::filament/resources/activity-type/pages/edit-activity-type.notification.title'))
            ->body(__('support::filament/resources/activity-type/pages/edit-activity-type.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('support::filament/resources/activity-type/pages/edit-activity-type.header-actions.delete.notification.title'))
                        ->body(__('support::filament/resources/activity-type/pages/edit-activity-type.header-actions.delete.notification.body')),
                ),
        ];
    }
}
