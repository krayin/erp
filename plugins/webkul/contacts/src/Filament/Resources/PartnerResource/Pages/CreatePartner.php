<?php

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Webkul\Contact\Filament\Resources\PartnerResource;

class CreatePartner extends CreateRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('contacts::filament/resources/partner/pages/create-partner.title');
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('contacts::filament/resources/partner/pages/create-partner.notification.title'))
            ->body(__('contacts::filament/resources/partner/pages/create-partner.notification.body'));
    }
}
