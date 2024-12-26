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
            ->body('The activity plan has been saved successfully.');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Activity plan deleted')
                        ->body('The activity plan has been deleted successfully.'),
                ),
        ];
    }
}
