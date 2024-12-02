<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

class FollowerAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'follower.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->icon('heroicon-s-user-plus')
            ->color('gray')
            ->modalHeading('Manage Followers')
            ->modalDescription('Search and manage followers for this record')
            ->modalWidth(MaxWidth::SevenExtraLarge)
            ->form([
                Forms\Components\TextInput::make('search_users')
                    ->label('Search Users')
                    ->placeholder('Search by name or email')
                    ->live()
                    ->debounce(500)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('user_results', $this->getUsersQuery($state)->get())),

                Forms\Components\Repeater::make('user_results')
                    ->label('Search Results')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name'),
                        TextEntry::make('email')
                            ->label('Email'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('add_follower')
                                ->label('Add')
                                ->color('success')
                                ->icon('heroicon-s-user-plus')
                                ->action(function (Model $record, array $data) {
                                    // Add user as a follower
                                    $record->followers()->attach($data['id']);

                                    Notification::make()
                                        ->success()
                                        ->title('Follower Added')
                                        ->body("{$data['name']} has been added as a follower.")
                                        ->send();
                                }),
                        ]),
                    ])
                    ->hidden(fn ($get) => empty($get('user_results'))),

                Forms\Components\Section::make('Current Followers')
                    ->schema([
                        Forms\Components\Repeater::make('followers')
                            ->relationship('followers')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Follower Name'),
                                TextEntry::make('email')
                                    ->label('Email Address'),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('remove')
                                        ->label('Remove')
                                        ->color('danger')
                                        ->icon('heroicon-s-trash')
                                        ->action(function (Model $record, $data) {
                                            $record->followers()->detach($data['id']);

                                            Notification::make()
                                                ->warning()
                                                ->title('Follower Removed')
                                                ->body('Follower has been removed.')
                                                ->send();
                                        }),
                                ]),
                            ]),
                    ]),
            ])
            ->badge(fn (Model $record): int => $record->followers()->count())
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }

    /**
     * Get users query for search
     */
    protected function getUsersQuery(?string $search = null)
    {
        $query = User::query()
            ->whereNotIn(
                'id',
                fn ($q) => $q->select('user_id')
                    ->from('followers')
                    ->where('record_type', get_class($this->record))
                    ->where('record_id', $this->record->id)
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
