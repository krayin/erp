<?php

namespace Webkul\Security\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Mail;
use Webkul\Security\Filament\Resources\UserResource;
use Webkul\Security\Mail\UserInvitationMail;
use Webkul\Security\Models\Invitation;
use Webkul\Security\Models\User;
use Webkul\Security\Settings\UserSettings;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('security::app.filament.resources.user.pages.list.tabs.all'))
                ->badge(User::count()),
            'archived' => Tab::make(__('security::app.filament.resources.user.pages.list.tabs.archived'))
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
                ->label(__('security::app.filament.resources.user.pages.list.header-actions.invite-user.title'))
                ->icon('heroicon-o-envelope')
                ->modalIcon('heroicon-o-envelope')
                ->modalSubmitActionLabel(__('security::app.filament.resources.user.pages.list.header-actions.invite-user.modal.submit-action-label'))
                ->visible(fn (UserSettings $userSettings) => $userSettings->enable_user_invitation)
                ->form([
                    TextInput::make('email')
                        ->email()
                        ->label(__('security::app.filament.resources.user.pages.list.header-actions.form.email'))
                        ->required(),
                ])
                ->action(function ($data) {
                    $invitation = Invitation::create(['email' => $data['email']]);

                    Mail::to($invitation->email)->send(new UserInvitationMail($invitation));

                    Notification::make('invitedSuccess')
                        ->body(__('security::app.filament.resources.user.pages.list.header-actions.invite-user.notification.title'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
