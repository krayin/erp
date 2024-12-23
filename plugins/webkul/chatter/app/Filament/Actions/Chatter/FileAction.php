<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Closure;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\MaxWidth;
use Illuminate\View\View;

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
            ->badge(fn($record) => $record->attachments()->count())
            ->form([
                Forms\Components\FileUpload::make('files')
                    ->label(__('chatter::app.filament.actions.chatter.file.form.file'))
                    ->multiple()
                    ->directory('chats-attachments')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
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
                        'text/plain'
                    ])
                    ->maxSize(10240)
                    ->helperText('Max file size: 10MB. Allowed types: Images, PDF, Word, Excel, Text')
                    ->columnSpanFull()
                    ->required(),
            ])
            ->action(function (array $data, ?Model $record): void {
                try {
                    $record->attachments()
                        ->createMany(
                            collect($data['files'] ?? [])
                                ->map(fn($filePath) => [
                                    'file_path'          => $filePath,
                                    'original_file_name' => basename($filePath),
                                    'mime_type'          => mime_content_type($storagePath = storage_path('app/public/' . $filePath)) ?: 'application/octet-stream',
                                    'file_size'          => filesize($storagePath) ?: 0,
                                ])
                                ->filter()
                                ->toArray()
                        );

                    Notification::make()
                        ->success()
                        ->title(__('chatter::app.filament.actions.chatter.file.action.notification.success.title'))
                        ->body(__('Files uploaded successfully'))
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::app.filament.actions.chatter.file.action.notification.danger.title'))
                        ->body(__('Failed to upload files'))
                        ->send();

                    report($e);
                }
            })
            ->modalContentFooter(fn(Model $record): View => view('chatter::filament.actions.files', [
                'attachments' => $record->attachments()->latest()->get() ?? collect(),
            ]))
            ->label('Attachments')
            ->icon('heroicon-o-paper-clip')
            ->iconPosition(IconPosition::Before)
            ->modalSubmitAction(fn($action) => $action
                ->label('Upload')
                ->icon('heroicon-m-paper-airplane'))
            ->modalWidth(MaxWidth::ThreeExtraLarge)
            ->slideOver(false);
    }
}
