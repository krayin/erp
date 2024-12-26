<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

class FollowerAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'add.follower.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-s-user')
            ->color('gray')
            ->modal()
            ->modalIcon('heroicon-s-user-plus')
            ->badge(fn (Model $record): int => $record->followers->count())
            ->modalWidth(MaxWidth::Large)
            ->slideOver(false)
            ->form(function (Form $form) {
                return $form
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Recipients')
                            ->searchable()
                            ->preload()
                            ->searchable()
                            ->getSearchResultsUsing(function (string $query, Model $record) {
                                return User::whereNotIn('id', $record->followers->pluck('user_id'))
                                    ->where('name', 'like', "%{$query}%")
                                    ->limit(10)
                                    ->pluck('name', 'id');
                            })
                            ->required(),
                        Forms\Components\Toggle::make('notify')
                            ->label('Notify User'),
                    ])
                    ->columns(1);
            })
            ->modalContentFooter(function (Model $record) {
                return view('chatter::filament.actions.follower-action', [
                    'record' => $record,
                ]);
            })
            ->action(function (Model $record, array $data, FollowerAction $action) {
                $user = User::findOrFail($data['user_id']);

                $record->addFollower($user);

                Notification::make()
                    ->success()
                    ->title('Success')
                    ->body("\"{$user->name}\" has been added as a follower.")
                    ->send();
            })
            ->modalSubmitAction(
                fn ($action) => $action
                    ->label('Add Follower')
                    ->icon('heroicon-m-user-plus')
            );
    }
}
