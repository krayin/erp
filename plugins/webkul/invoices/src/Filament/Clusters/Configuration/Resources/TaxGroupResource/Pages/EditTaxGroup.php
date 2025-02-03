<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTaxGroup extends EditRecord
{
    protected static string $resource = TaxGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('invoices::filament/clusters/configurations/resources/tax-group/pages/edit-tax-group.notification.title'))
            ->body(__('invoices::filament/clusters/configurations/resources/tax-group/pages/edit-tax-group.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/tax-group/pages/edit-tax-group.header-actions.delete.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/tax-group/pages/edit-tax-group.header-actions.delete.notification.body'))
                )
        ];
    }
}
