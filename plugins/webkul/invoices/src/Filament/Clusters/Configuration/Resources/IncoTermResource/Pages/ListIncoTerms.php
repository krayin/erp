<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\IncoTermResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\IncoTermResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListIncoTerms extends ListRecords
{
    protected static string $resource = IncoTermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/inco-term/pages/list-inco-term.header-actions.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/inco-term/pages/list-inco-term.header-actions.notification.body'))
                )
        ];
    }
}
