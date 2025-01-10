<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\StageResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\StageResource;

class ViewStages extends ViewRecord
{
    protected static string $resource = StageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/configurations/resources/stage/pages/view-stage.header-actions.delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/configurations/resources/stage/pages/view-stage.header-actions.delete.notification.body'))
                ),
        ];
    }
}
