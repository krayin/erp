<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\AccountResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\AccountResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('invoices::filament/clusters/configurations/resources/account/pages/edit-account.notification.title'))
            ->body(__('invoices::filament/clusters/configurations/resources/account/pages/edit-account.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/account/pages/edit-account.header-actions.delete.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/account/pages/edit-account.header-actions.delete.notification.body'))
                ),
        ];
    }
}
