<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;

class FileAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'file.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->outlined()
            ->tooltip('Upload Files')
            ->badge(fn ($record) => $record->attachments()->count())
            ->form([
                Forms\Components\FileUpload::make('files')
                    ->label(__('chatter::app.filament.actions.chatter.file.form.file'))
                    ->multiple()
                    ->directory('chats-attachments')
                    ->downloadable()
                    ->openable()
                    ->reorderable()
                    ->previewable(true)
                    ->deletable()
                    ->panelLayout('grid')
                    ->imagePreviewHeight('100')
                    ->uploadingMessage('Uploading attachment...')
                    ->deleteUploadedFileUsing(function ($file, ?Model $record) {
                        $attachment = $record->attachments()
                            ->where('file_path', $file)
                            ->first();

                        if ($attachment) {
                            $attachment->delete();

                            Notification::make()
                                ->success()
                                ->title('File Deleted')
                                ->body('File has been deleted successfully')
                                ->send();
                        }
                    })
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
                    ->columnSpanFull()
                    ->required()
                    ->default(function (?Model $record) {
                        if (! $record) {
                            return [];
                        }

                        return $record->attachments()
                            ->latest()
                            ->get()
                            ->pluck('file_path')
                            ->toArray() ?? [];
                    }),
            ])
            ->action(function (FileAction $action, array $data, ?Model $record): void {
                try {
                    $existingFiles = $record->attachments()
                        ->latest()
                        ->get()
                        ->pluck('file_path')
                        ->toArray();

                    $newFiles = array_filter($data['files'] ?? [], function ($file) use ($existingFiles) {
                        return ! in_array($file, $existingFiles);
                    });

                    // Only proceed if there are new files to upload
                    if (! empty($newFiles)) {
                        $record->addAttachments($newFiles);

                        Notification::make()
                            ->success()
                            ->title(__('chatter::app.filament.actions.chatter.file.action.notification.success.title'))
                            ->body(__('Files uploaded successfully'))
                            ->send();
                    } else {
                        Notification::make()
                            ->info()
                            ->title('No New Files')
                            ->body('All files have already been uploaded')
                            ->send();
                    }
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::app.filament.actions.chatter.file.action.notification.danger.title'))
                        ->body(__('Failed to upload files'))
                        ->send();

                    report($e);
                }

                $action->halt();

                $action->resetFormData();
            })
            ->label('Attachments')
            ->icon('heroicon-o-paper-clip')
            ->modalIcon('heroicon-o-paper-clip')
            ->iconPosition(IconPosition::Before)
            ->modalSubmitAction(
                fn ($action) => $action
                    ->label('Upload')
                    ->icon('heroicon-m-paper-airplane')
            )
            ->modalWidth(MaxWidth::ThreeExtraLarge)
            ->slideOver(false);
    }
}
