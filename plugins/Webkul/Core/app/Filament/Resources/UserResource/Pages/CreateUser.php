<?php

namespace Webkul\Core\Filament\Resources\UserResource\Pages;

use Webkul\Core\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User registered';
    }
}
