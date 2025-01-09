<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\RefuseReasonResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\RefuseReasonResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListRefuseReasons extends ListRecords
{
    protected static string $resource = RefuseReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title(__('recruitments::filament/clusters/configurations/resources/refuse-reason/pages/list-refuse-reasons.notification.title'))
                    ->body(__('recruitments::filament/clusters/configurations/resources/refuse-reason/pages/list-refuse-reasons.notification.body'))
            ),
        ];
    }
}
