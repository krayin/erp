<?php

namespace Webkul\Security\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Security\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('security::app.filament.resources.user.pages.create.created-notification-title');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = parent::handleRecordCreation($data);

        $partner = $user->partner()->create([
            'creator_id' => Auth::user()->id,
            'user_id'    => $user->id,
            'company_id' => $data['default_company_id'] ?? null,
            'avatar'     => $data['avatar'] ?? null,
            ...$data,
        ]);

        $user->update([
            'partner_id' => $partner->id,
        ]);

        return $user;
    }
}
