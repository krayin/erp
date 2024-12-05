<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Closure;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Webkul\Chatter\Mail\SendMessage;

class MessageAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'message.action';
    }

    public function record(Model|Closure|null $record = null): static
    {
        $this->record = $record;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->outlined()
            ->form(
                fn($form) => $form->schema([
                    Forms\Components\RichEditor::make('content')
                        ->hiddenLabel()
                        ->placeholder(__('chatter::app.filament.actions.chatter.activity.form.type-your-message-here'))
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Hidden::make('type')
                        ->default('message'),
                ])
                    ->columns(1)
            )
            ->action(function (array $data, ?Model $record = null) {
                try {
                    $chat = $record->addChat($data, Auth::user()->id);

                    $this->notifyToFollowers($chat);

                    Notification::make()
                        ->success()
                        ->title(__('chatter::app.filament.actions.chatter.activity.action.notification.success.title'))
                        ->body(__('chatter::app.filament.actions.chatter.activity.action.notification.success.body'))
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::app.filament.actions.chatter.activity.action.notification.danger.title'))
                        ->body(__('chatter::app.filament.actions.chatter.activity.action.notification.danger.body'))
                        ->send();
                }
            })
            ->label(__('chatter::app.filament.actions.chatter.message.label'))
            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
            ->modalSubmitAction(function ($action) {
                $action->label(__('chatter::app.filament.actions.chatter.message.modal-submit-action.title'));
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }

    private function notifyToFollowers($chat): void
    {
        try {
            foreach ($this->getFollowers() as $follower) {
                if ($follower->id === Auth::user()->id) {
                    continue;
                }

                Mail::queue(new SendMessage($this->record, $follower, $chat));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    private function getFollowers()
    {
        return $this->record->followers()
            ->select('users.*')
            ->orderBy('name')
            ->get();
    }
}
