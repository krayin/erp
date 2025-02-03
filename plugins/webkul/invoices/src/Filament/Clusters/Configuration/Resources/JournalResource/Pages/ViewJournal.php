<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\JournalResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\JournalResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewJournal extends ViewRecord
{
    protected static string $resource = JournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('invoices::filament/clusters/configurations/resources/journal/pages/view-journal.notification.title'))
                        ->body(__('invoices::filament/clusters/configurations/resources/journal/pages/view-journal.notification.body'))
                ),
        ];
    }
}
