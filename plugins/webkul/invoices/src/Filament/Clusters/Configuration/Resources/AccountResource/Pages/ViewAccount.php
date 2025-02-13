<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\AccountResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\AccountResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/account/pages/view-account.header-actions.delete.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/account/pages/view-account.header-actions.delete.notification.body'))
                ),
        ];
    }
}
