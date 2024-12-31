<?php

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Webkul\Contact\Filament\Resources\PartnerResource;
use Webkul\Chatter\Filament\Actions\ChatterAction;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('contacts::filament/resources/partner/pages/edit-partner.title');
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('contacts::filament/resources/partner/pages/edit-partner.notification.title'))
            ->body(__('contacts::filament/resources/partner/pages/edit-partner.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('contacts::filament/resources/partner/pages/edit-partner.header-actions.delete.notification.title'))
                        ->body(__('contacts::filament/resources/partner/pages/edit-partner.header-actions.delete.notification.body')),
                ),
        ];
    }
}
