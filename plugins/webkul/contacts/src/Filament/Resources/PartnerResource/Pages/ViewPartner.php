<?php

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Contact\Filament\Resources\PartnerResource;
use Illuminate\Contracts\Support\Htmlable;

class ViewPartner extends ViewRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('contacts::filament/resources/partner/pages/view-partner.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('contacts::filament/resources/partner/pages/view-partner.header-actions.delete.notification.title'))
                        ->body(__('contacts::filament/resources/partner/pages/view-partner.header-actions.delete.notification.body')),
                ),
        ];
    }
}
