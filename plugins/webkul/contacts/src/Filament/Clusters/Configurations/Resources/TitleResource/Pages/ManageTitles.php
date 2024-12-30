<?php

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources\TitleResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Contact\Filament\Clusters\Configurations\Resources\TitleResource;

class ManageTitles extends ManageRecords
{
    protected static string $resource = TitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('contacts::filament/clusters/configurations/resources/title/pages/manage-titles.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('contacts::filament/clusters/configurations/resources/title/pages/manage-titles.header-actions.create.notification.title'))
                        ->body(__('contacts::filament/clusters/configurations/resources/title/pages/manage-titles.header-actions.create.notification.body')),
                ),
        ];
    }
}
