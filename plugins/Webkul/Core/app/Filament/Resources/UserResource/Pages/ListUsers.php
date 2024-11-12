<?php

namespace Webkul\Core\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Mail;
use Webkul\Core\Filament\Resources\UserResource;
use Webkul\Core\Mail\UserInvitationMail;
use Webkul\Core\Models\Invitation;
use Webkul\Core\Models\User;
use Webkul\Core\Settings\UserSettings;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users')
                ->badge(User::count()),
            'archived' => Tab::make('Archived')
                ->badge(User::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-user-plus'),
            Actions\Action::make('inviteUser')
                ->icon('heroicon-o-envelope')
                ->modalIcon('heroicon-o-envelope')
                ->modalSubmitActionLabel('Invite User')
                ->visible(fn (UserSettings $userSettings) => $userSettings->enable_user_invitation)
                ->form([
                    TextInput::make('email')
                        ->email()
                        ->required(),
                ])
                ->action(function ($data) {
                    $invitation = Invitation::create(['email' => $data['email']]);

                    Mail::to($invitation->email)->send(new UserInvitationMail($invitation));

                    Notification::make('invitedSuccess')
                        ->body('User invited successfully!')
                        ->success()
                        ->send();
                }),
        ];
    }
}
