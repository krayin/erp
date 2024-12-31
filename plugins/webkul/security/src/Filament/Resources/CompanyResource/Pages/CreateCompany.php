<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Security\Filament\Resources\CompanyResource;
use Webkul\Support\Models\Company;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('security::filament/resources/company/pages/create-company.notification.title'))
            ->body(__('security::filament/resources/company/pages/create-company.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [
            'user_id'  => Auth::user()->id,
            'sequence' => Company::max('sequence') + 1,
            ...$data,
        ];
    }
}
