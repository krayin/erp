<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Closure;
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
                    Forms\Components\RichEditor::make('content')
                        ->hiddenLabel()
                        ->placeholder('Type your message here...')
                        ->required(),
                    Forms\Components\Hidden::make('type')
                        ->default('note'),
                ])
                    ->columns(1)
            )
            ->action(function (array $data, ?Model $record = null) {
                try {
                    $record->addChat($data, Auth::user()->id);

                    Notification::make()
                        ->success()
                        ->title('Log Added')
                        ->body('Your log note has been added successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Log Not Added')
                        ->body('An error occurred: '.$e->getMessage())
                        ->send();
                }
            })
            ->label('Log Note')
            ->icon('heroicon-o-chat-bubble-oval-left')
            ->modalSubmitAction(function ($action) {
                $action->label('Log');
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }
}
