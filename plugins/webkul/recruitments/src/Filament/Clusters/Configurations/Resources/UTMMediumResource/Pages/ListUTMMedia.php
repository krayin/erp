<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\UTMMediumResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\UTMMediumResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUTMMedia extends ListRecords
{
    protected static string $resource = UTMMediumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('recruitments::filament/clusters/configurations/resources/utm/pages/list-utm.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/configurations/resources/utm/pages/list-utm.header-actions.create.notification.title'))
                        ->body(__('recruitments::filament/clusters/configurations/resources/utm/pages/list-utm.header-actions.create.notification.body'))
                )
        ];
    }
}
