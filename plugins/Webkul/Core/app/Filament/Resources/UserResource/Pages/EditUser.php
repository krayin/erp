<?php

namespace Webkul\Core\Filament\Resources\UserResource\Pages;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Filament\Forms;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Core\Filament\Resources\UserResource;
use Webkul\Core\Models\User;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Webkul\Chatter\Filament\Actions\ChatterAction::make(),
            Actions\Action::make('changePassword') 
                ->action(function (User $record, array $data): void {
                    $record->update([
                        'password' => $data['new_password'],
                    ]);

                    Notification::make()
                        ->title('Saved successfully')
                        ->success()
                        ->send();
                })
                ->form([
                    Forms\Components\TextInput::make('new_password')
                        ->password()
                        ->label('New Password')
                        ->required()
                        ->rule(Password::default()),
                    Forms\Components\TextInput::make('new_password_confirmation')
                        ->password()
                        ->label('Confirm New Password')
                        ->rule('required', fn($get) => ! ! $get('new_password'))
                        ->same('new_password'),
                ])
                ->icon('heroicon-o-key'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
