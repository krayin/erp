<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Closure;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FileAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'log.action';
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
                fn ($form) => $form->schema([
                    Forms\Components\FileUpload::make('file')
                        ->label(__('chatter::app.filament.actions.chatter.file.form.file'))
                        ->multiple()
                        ->directory('chats-attachments')
                        ->panelLayout('grid')
                        ->required(),
                    Forms\Components\Hidden::make('type')
                        ->default('file'),
                ])
                    ->columns(1)
            )
            ->action(function (array $data, ?Model $record = null) {
                try {
                    $chat = $record->addChat($data, Auth::user()->id);

                    $chat->attachments()
                        ->createMany(
                            collect($data['file'] ?? [])
                                ->map(fn ($filePath) => [
                                    'file_path'          => $filePath,
                                    'original_file_name' => basename($filePath),
                                    'mime_type'          => mime_content_type($storagePath = storage_path('app/public/'.$filePath)) ?: 'application/octet-stream',
                                    'file_size'          => filesize($storagePath) ?: 0,
                                ])
                                ->filter()
                                ->toArray()
                        );

                    Notification::make()
                        ->success()
                        ->title(__('chatter::app.filament.actions.chatter.file.action.notification.success.title'))
                        ->body(__('chatter::app.filament.actions.chatter.file.action.notification.success.body'))
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::app.filament.actions.chatter.file.action.notification.danger.title'))
                        ->body(__('chatter::app.filament.actions.chatter.file.action.notification.danger.body'))
                        ->send();

                    report($e);
                }
            })
            ->label(__('chatter::app.filament.actions.chatter.file.action.label'))
            ->icon('heroicon-o-document-text')
            ->modalSubmitAction(function ($action) {
                $action->label(__('chatter::app.filament.actions.chatter.file.action.modal-submit-action.title'));
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }
}
