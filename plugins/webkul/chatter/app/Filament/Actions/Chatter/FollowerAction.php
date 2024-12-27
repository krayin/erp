<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Webkul\Partner\Models\Partner;

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
            ->modalWidth(MaxWidth::TwoExtraLarge)
            ->slideOver(false)
            ->form(function (Form $form) {
                return $form
                    ->schema([
                        Forms\Components\Select::make('partner_id')
                            ->label('Recipients')
                            ->searchable()
                            ->preload()
                            ->searchable()
                            ->relationship('followable', 'name')
                            ->required(),
                        Forms\Components\Toggle::make('notify')
                            ->live()
                            ->label('Notify User'),
                        Forms\Components\RichEditor::make('note')
                            ->disableGrammarly()
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->visible(fn (Get $get) => $get('notify'))
                            ->hiddenLabel()
                            ->placeholder('Add a note...'),
                    ])
                    ->columns(1);
            })
            ->modalContentFooter(function (Model $record) {
                return view('chatter::filament.actions.follower-action', [
                    'record' => $record,
                ]);
            })
            ->action(function (Model $record, array $data, FollowerAction $action) {
                $partner = Partner::findOrFail($data['partner_id']);

                $record->addFollower($partner);

                Notification::make()
                    ->success()
                    ->title('Success')
                    ->body("\"{$partner->name}\" has been added as a follower.")
                    ->send();
            })
            ->modalSubmitAction(
                fn ($action) => $action
                    ->label('Add Follower')
                    ->icon('heroicon-m-user-plus')
            );
    }
}
