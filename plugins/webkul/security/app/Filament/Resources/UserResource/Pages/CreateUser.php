<?php

namespace Webkul\Security\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Security\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User registered';
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
