<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
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
            ->mountUsing(function (Form $form) {
                $form->fill();
            })
            ->form(
                fn($form) => $form->schema([
                    Forms\Components\Group::make([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('add_subject')
                                ->label(function ($get) {
                                    return $get('showSubject') ? __('chatter::filament/resources/actions/chatter/log-action.setup.form.fields.hide-subject') : __('chatter::filament/resources/actions/chatter/log-action.setup.form.fields.add-subject');
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
                        ->placeholder(__('chatter::filament/resources/actions/chatter/log-action.setup.form.fields.subject'))
                        ->live()
                        ->visible(fn($get) => $get('showSubject'))
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('body')
                        ->hiddenLabel()
                        ->placeholder(__('chatter::filament/resources/actions/chatter/log-action.setup.form.fields.write-message-here'))
                        ->required()
                        ->fileAttachmentsDirectory('log-attachments')
                        ->disableGrammarly()
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('attachments')
                        ->hiddenLabel()
                        ->multiple()
                        ->directory('log-attachments')
                        ->previewable(true)
                        ->panelLayout('grid')
                        ->imagePreviewHeight('100')
                        ->disableGrammarly()
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
                        ->helperText(__('chatter::filament/resources/actions/chatter/log-action.setup.form.fields.attachments-helper-text'))
                        ->columnSpanFull(),
                    Forms\Components\Hidden::make('type')
                        ->default('note'),
                ])
                    ->columns(1)
            )
            ->action(function (array $data, ?Model $record = null) {
                try {
                    $data['name'] = $record->name;
                    $data['causer_type'] = Auth::user()?->getMorphClass();
                    $data['causer_id'] = Auth::id();

                    $message = $record->addMessage($data, Auth::user()->id);

                    if (! empty($data['attachments'])) {
                        $record->addAttachments(
                            $data['attachments'],
                            ['message_id' => $message->id],
                        );
                    }

                    Notification::make()
                        ->success()
                        ->title(__('chatter::filament/resources/actions/chatter/log-action.setup.actions.notification.success.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/log-action.setup.actions.notification.success.body'))
                        ->send();
                } catch (\Exception $e) {
                    report($e);
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::filament/resources/actions/chatter/log-action.setup.actions.notification.error.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/log-action.setup.actions.notification.error.body'))
                        ->send();
                }
            })
            ->label(__('chatter::filament/resources/actions/chatter/log-action.setup.title'))
            ->icon('heroicon-o-chat-bubble-oval-left')
            ->modalIcon('heroicon-o-chat-bubble-oval-left')
            ->modalSubmitAction(function ($action) {
                $action->label(__('chatter::filament/resources/actions/chatter/log-action.setup.submit-title'));
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }
}
