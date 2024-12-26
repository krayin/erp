<?php

namespace Webkul\Chatter\Filament\Actions\Chatter\FollowerActions;

use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;

class AddFollowerAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'add.follower.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-s-user-plus')
            ->color('gray')
            ->modal()
            ->modalIcon('heroicon-s-user-plus')
            ->modalWidth(MaxWidth::Large)
            ->slideOver(false)
            ->form(function (Form $form, Model $record) {
                return $form
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Recipients')
                            ->searchable()
                            ->preload()
                            ->searchable()
                            ->options($record->nonFollowers()->pluck('name', 'id'))
                            ->required(),
                        Forms\Components\Toggle::make('notify')
                            ->label('Notify User')
                    ])
                    ->columns(1);
            })
            ->action(function (Model $record, array $data) {
                $user = User::findOrFail($data['user_id']);

                $record->addFollower($user);
            })
            ->modalSubmitAction(
                fn($action) => $action
                    ->label('Add Follower')
                    ->icon('heroicon-m-user-plus')
            );
    }
}
