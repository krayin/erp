<?php

namespace Webkul\Core\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Core\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User registered';
    }
}
