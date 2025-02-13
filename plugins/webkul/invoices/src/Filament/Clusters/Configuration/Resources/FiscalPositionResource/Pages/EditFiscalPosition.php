<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\FiscalPositionResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\FiscalPositionResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Account\Models\FiscalPosition;

class EditFiscalPosition extends EditRecord
{
    protected static string $resource = FiscalPositionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('invoices::filament/clusters/configurations/resources/fiscal-position/pages/edit-fiscal-position.notification.title'))
            ->body(__('invoices::filament/clusters/configurations/resources/fiscal-position/pages/edit-fiscal-position.notification.body'));
    }

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/fiscal-position/pages/edit-fiscal-position.header-actions.delete.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/fiscal-position/pages/edit-fiscal-position.header-actions.delete.notification.body'))
                )
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();

        $data['company_id'] = $user?->default_company_id;

        $data['sort'] = FiscalPosition::max('sort') + 1;

        return $data;
    }
}
