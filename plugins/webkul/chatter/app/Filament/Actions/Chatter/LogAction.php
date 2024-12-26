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
                fn($form) => $form->schema([
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
                        ->visible(fn($get) => $get('showSubject'))
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('body')
                        ->hiddenLabel()
                        ->placeholder(__('chatter::app.filament.actions.chatter.activity.form.type-your-message-here'))
                        ->required()
                        ->columnSpanFull(),
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
                        ->default('note'),
                ])
                    ->columns(1)
            )
            ->action(function (array $data, ?Model $record = null) {
                try {
                    $data['name'] = $record->name;
                    $data['causer_type'] = Auth::user()?->getMorphClass();
                    $data['causer_id'] = Auth::id();

                    $record->addMessage($data, Auth::user()->id);

                    if (! empty($data['attachments'])) {
                        $record->addAttachments(
                            $data['attachments'],
                            ['message_id' => $message->id],
                        );
                    }

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
            ->modalIcon('heroicon-o-chat-bubble-oval-left')
            ->modalSubmitAction(function ($action) {
                $action->label(__('chatter::app.filament.actions.chatter.log.modal-submit-action.log'));
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }
}
