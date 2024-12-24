<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'log.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->outlined()
            ->form(
                fn ($form) => $form->schema([
                    Forms\Components\TextInput::make('subject')
                        ->placeholder('Subject')
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('body')
                        ->hiddenLabel()
                        ->placeholder(__('chatter::app.filament.actions.chatter.activity.form.type-your-message-here'))
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Hidden::make('type')
                        ->default('note'),
                ])
                    ->columns(1)
            )
            ->action(function (array $data, ?Model $record = null) {
                try {
                    $data['name'] = $record->name;

                    $chat = $record->addMessage($data, Auth::user()->id);

                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Lognote sent successfully')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Error')
                        ->body('An error occurred while sending the lognote')
                        ->send();

                    report($e);
                }
            })
            ->label(__('chatter::app.filament.actions.chatter.log.label'))
            ->icon('heroicon-o-chat-bubble-oval-left')
            ->modalSubmitAction(function ($action) {
                $action->label(__('chatter::app.filament.actions.chatter.log.modal-submit-action.log'));
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }
}
