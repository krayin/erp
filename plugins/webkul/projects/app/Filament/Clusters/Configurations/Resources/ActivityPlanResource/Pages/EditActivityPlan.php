<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource;

class EditActivityPlan extends EditRecord
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Activity plan updated')
            ->body('The activity plan has been saved successfully.')
            ->title(__('projects::app.filament.clusters.configurations.resources.activity-plan.pages.edit.notification.title'))
            ->body(__('projects::app.filament.clusters.configurations.resources.activity-plan.pages.edit.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('projects::app.filament.clusters.configurations.resources.activity-plan.pages.edit.header-actions.delete.notification.title'))
                        ->body(__('projects::app.filament.clusters.configurations.resources.activity-plan.pages.edit.header-actions.delete.notification.body')),
                ),
        ];
    }
}
