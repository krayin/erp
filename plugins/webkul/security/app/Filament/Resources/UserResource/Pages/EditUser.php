<?php

namespace Webkul\Security\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Filament\Resources\UserResource;
use Webkul\Security\Models\User;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('changePassword')
                ->label(__('security::app.filament.resources.user.pages.edit.header-actions.action.title'))
                ->action(function (User $record, array $data): void {
                    $record->update([
                        'password' => Hash::make($data['new_password']),
                    ]);

                    Notification::make()
                        ->title(__('security::app.filament.resources.user.pages.edit.header-actions.action.notification.title'))
                        ->success()
                        ->send();
                })
                ->form([
                    Forms\Components\TextInput::make('new_password')
                        ->password()
                        ->label(__('security::app.filament.resources.user.pages.edit.header-actions.form.new-password'))
                        ->required()
                        ->rule(Password::default()),
                    Forms\Components\TextInput::make('new_password_confirmation')
                        ->password()
                        ->label(__('security::app.filament.resources.user.pages.edit.header-actions.form.confirm-new-password'))
                        ->rule('required', fn($get) => (bool) $get('new_password'))
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $partner = $this->record->partner;

        return [
            ...$data,
            ...$partner ? $partner->toArray() : [],
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $partner = Partner::updateOrCreate(
            ['user_id' => $record->id],
            [
                'creator_id' => Auth::user()->id,
                'user_id'    => $record->id,
                'company_id' => $data['default_company_id'] ?? null,
                'avatar'     => $data['avatar'] ?? null,
                ...$data,
            ],
        );

        $record->partner_id = $partner->id;
        $record->save();

        return parent::handleRecordUpdate($record, $data);
    }
}
