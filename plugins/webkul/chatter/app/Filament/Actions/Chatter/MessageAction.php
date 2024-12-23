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
                    Forms\Components\TextInput::make('subject')
                        ->placeholder('Subject')
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('body')
                        ->hiddenLabel()
                        ->placeholder(__('chatter::app.filament.actions.chatter.activity.form.type-your-message-here'))
                        ->required()
                        ->default('This is sample test message')
                        ->columnSpanFull(),
                    Forms\Components\Hidden::make('type')
                        ->default('comment'),
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
                        ->body('Message sent successfully')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Error')
                        ->body('Failed to send message')
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
