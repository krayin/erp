<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Support\Services\EmailTemplateService;

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
                                return $get('showSubject') ? __('chatter::filament/resources/actions/chatter/log-action.setup.form.fields.hide-subject') : __('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.add-subject');
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
                            ->icon(fn (Get $get) => ! $get('showSubject') ? 'heroicon-s-plus' : 'heroicon-s-minus'),
                    ])
                        ->columnSpan('full')
                        ->alignRight(),
                ]),
                Forms\Components\TextInput::make('subject')
                    ->placeholder(__('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.subject'))
                    ->live()
                    ->visible(fn ($get) => $get('showSubject')),
                Forms\Components\RichEditor::make('body')
                    ->hiddenLabel()
                    ->placeholder(__('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.write-message-here'))
                    ->fileAttachmentsDirectory('log-attachments')
                    ->disableGrammarly()
                    ->required(),
                Forms\Components\FileUpload::make('attachments')
                    ->hiddenLabel()
                    ->multiple()
                    ->directory('messages-attachments')
                    ->disableGrammarly()
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
                    ->helperText(__('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.attachments-helper-text'))
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('type')
                    ->default('comment'),
            ])
            ->action(function (array $data, ?Model $record = null) {
                try {
                    $data['name'] = $record->name;

                    $message = $record->addMessage($data, Auth::user()->id);

                    if (! empty($data['attachments'])) {
                        $record->addAttachments(
                            $data['attachments'],
                            ['message_id' => $message->id],
                        );
                    }

                    $this->notifyToFollowers($record, $message);

                    Notification::make()
                        ->success()
                        ->title(__('chatter::filament/resources/actions/chatter/message-action.setup.actions.notification.success.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/message-action.setup.actions.notification.success.body'))
                        ->send();
                } catch (\Exception $e) {
                    report($e);
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::filament/resources/actions/chatter/message-action.setup.actions.notification.error.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/message-action.setup.actions.notification.error.body'))
                        ->send();
                }
            })
            ->label(__('chatter::filament/resources/actions/chatter/message-action.setup.title'))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->modalIcon('heroicon-o-chat-bubble-left-right')
            ->modalSubmitAction(function ($action) {
                $action->label(__('chatter::filament/resources/actions/chatter/message-action.setup.submit-title'));
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }

    private function notifyToFollowers(mixed $record, $message): void
    {
        try {
            foreach ($record->followers as $follower) {
                if ($follower?->partner) {
                    $variables = [
                        'record_name'    => $follower?->partner->name,
                        'author_name'    => Auth::user()->name,
                        'author_email'   => Auth::user()->email,
                        'model_name'     => class_basename($record),
                        'content'        => $message->body ?? '',
                        'from'           => [
                            'address' => Auth::user()->email,
                            'name'    => Auth::user()->name,
                        ],
                    ];

                    app(EmailTemplateService::class)->send(
                        templateCode: 'message_template',
                        recipientEmail: $follower?->partner->email,
                        recipientName: $follower?->partner->name,
                        variables: $variables,
                        attachments: $message->attachments?->map(function ($attachment) {
                            return [
                                'path' => asset($attachment->url),
                                'name' => $attachment->name,
                                'mime' => $attachment->mime,
                            ];
                        })->toArray(),
                    );
                }
            }
        } catch (\Exception $e) {
            report($e);
            dd($e);
        }
    }
}
