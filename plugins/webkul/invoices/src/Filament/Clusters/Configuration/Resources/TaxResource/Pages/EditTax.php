<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;

class EditTax extends EditRecord
{
    protected static string $resource = TaxResource::class;

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('invoices::filament/clusters/configurations/resources/tax/pages/edit-tax.notification.title'))
            ->body(__('invoices::filament/clusters/configurations/resources/tax/pages/edit-tax.notification.body'));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/tax/pages/edit-tax.header-actions.delete.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/tax/pages/edit-tax.header-actions.delete.notification.body'))
                )
        ];
    }
}
