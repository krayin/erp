<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\CashRoundingResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\CashRoundingResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCashRounding extends ViewRecord
{
    protected static string $resource = CashRoundingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/cash-rounding/pages/view-cash-rounding.header-actions.delete.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/cash-rounding/pages/view-cash-rounding.header-actions.delete.notification.body'))
                ),
        ];
    }
}
