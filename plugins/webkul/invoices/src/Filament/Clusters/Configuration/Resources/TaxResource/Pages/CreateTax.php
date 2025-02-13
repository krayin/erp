<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTax extends CreateRecord
{
    protected static string $resource = TaxResource::class;

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('invoices::filament/clusters/configurations/resources/tax/pages/create-tax.notification.title'))
            ->body(__('invoices::filament/clusters/configurations/resources/tax/pages/create-tax.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $data['company_id'] = $user->default_company_id;
        $data['creator_id'] = $user->id;

        return $data;
    }
}
