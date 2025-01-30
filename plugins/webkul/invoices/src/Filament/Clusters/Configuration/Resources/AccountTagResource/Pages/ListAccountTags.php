<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\AccountTagResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\AccountTagResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListAccountTags extends ListRecords
{
    protected static string $resource = AccountTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/account-tag/pages/list-account-tag.header-actions.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/account-tag/pages/list-account-tag.header-actions.notification.body'))
                ),
        ];
    }
}
