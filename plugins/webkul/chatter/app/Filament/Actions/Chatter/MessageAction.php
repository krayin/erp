<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\WithFileUploads;
use Webkul\Chatter\Mail\SendMessage;

class MessageAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'message.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->outlined()
            ->form([
                Forms\Components\Group::make([
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('add_subject')
                            ->label(function ($get) {
                                return $get('showSubject') ? 'Hide Subject' : 'Add Subject';
                            })
                            ->action(function ($set, $get) {
                                if ($get('showSubject')) {
                                    $set('showSubject', false);

                                    return;
                                }

                                $set('showSubject', true);
                            })
                            ->link()
                            ->size('sm')
                            ->icon('heroicon-s-plus'),
                    ])
                        ->columnSpan('full')
                        ->alignRight(),
                ]),
                Forms\Components\TextInput::make('subject')
                    ->placeholder('Subject')
                    ->live()
                    ->visible(fn($get) => $get('showSubject')),
                Forms\Components\RichEditor::make('body')
                    ->hiddenLabel()
                    ->placeholder(__('chatter::app.filament.actions.chatter.activity.form.type-your-message-here'))
                    ->required(),
                Forms\Components\FileUpload::make('attachments')
                    ->hiddenLabel()
                    ->multiple()
                    ->directory('messages-attachments')
                    ->previewable(true)
                    ->panelLayout('grid')
                    ->imagePreviewHeight('100')
                    ->acceptedFileTypes([
                        'image/*',
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/plain',
                    ])
                    ->maxSize(10240)
                    ->helperText('Max file size: 10MB. Allowed types: Images, PDF, Word, Excel, Text')
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('type')
                    ->default('comment'),
            ])
            ->action(function (MessageAction $action, array $data, ?Model $record = null) {
                try {
                    $data['name'] = $record->name;

                    $message = $record->addMessage($data, Auth::user()->id);

                    if (! empty($data['attachments'])) {
                        $record->addAttachments(
                            $data['attachments'],
                            ['message_id' => $message->id],
                        );
                    }

                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Message sent successfully')
                        ->send();
                } catch (\Exception $e) {
                    report($e);
                    Notification::make()
                        ->danger()
                        ->title('Error')
                        ->body('Failed to send message')
                        ->send();
                }
            })
            ->label(__('chatter::app.filament.actions.chatter.message.label'))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->modalIcon('heroicon-o-chat-bubble-left-right')
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
