<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\BankAccountResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\BankAccountResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBankAccounts extends ListRecords
{
    protected static string $resource = BankAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/bank-account/pages/list-bank-account.header-actions.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/bank-account/pages/list-bank-account.header-actions.notification.body'))
                ),
        ];
    }
}
