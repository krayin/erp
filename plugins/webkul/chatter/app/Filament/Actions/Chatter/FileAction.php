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
            ->slideOver()
            ->color('gray')
            ->form(
                fn($form) => $form->schema([
                    Forms\Components\FileUpload::make('file')
                        ->label('File')
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
                        ->title('File Sent')
                        ->body('Your file has been sent successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('File Sending Failed')
                        ->body('An error occurred: ' . $e->getMessage())
                        ->send();
                }
            })
            ->label('Files')
            ->icon('heroicon-o-document-text')
            ->label('Add Files')
            ->modalSubmitAction(function ($action) {
                $action->label('Send Files');
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }
}
