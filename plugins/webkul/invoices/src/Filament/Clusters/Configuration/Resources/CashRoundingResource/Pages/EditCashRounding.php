<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\CashRoundingResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\CashRoundingResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCashRounding extends EditRecord
{
    protected static string $resource = CashRoundingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('invoices::filament/clusters/configurations/resources/cash-rounding/pages/edit-cash-rounding.notification.title'))
            ->body(__('invoices::filament/clusters/configurations/resources/cash-rounding/pages/edit-cash-rounding.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/cash-rounding/pages/edit-cash-rounding.header-actions.delete.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/cash-rounding/pages/edit-cash-rounding.header-actions.delete.notification.body'))
                ),
        ];
    }
}
