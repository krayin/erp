<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DegreeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DegreeResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDegrees extends ListRecords
{
    protected static string $resource = DegreeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/configurations/resources/degree/pages/list-degree.notification.title'))
                        ->body(__('recruitments::filament/clusters/configurations/resources/degree/pages/list-degree.notification.body'))
                )
        ];
    }
}
