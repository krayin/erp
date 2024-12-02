<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Closure;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MessageAction extends Action
{
    protected bool $isExpanded = false;

    public static function getDefaultName(): ?string
    {
        return 'message.action';
    }

    public function record(Model|Closure|null $record = null): static
    {
        $this->record = $record;

        return $this;
    }

    public function expandForm(): static
    {
        $this->isExpanded = true;

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
                    Forms\Components\RichEditor::make('content')
                        ->hiddenLabel()
                        ->placeholder('Type your message here...')
                        ->required()
                        ->columnSpanFull(),
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
                        ->title('Message Sent')
                        ->body('Your message has been sent successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Message Sending Failed')
                        ->body('An error occurred: ' . $e->getMessage())
                        ->send();
                }
            })
            ->label('Message')
            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
            ->modalSubmitAction(function ($action) {
                $action->label('Send Message');
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }

    public function isFormExpanded(): bool
    {
        return $this->isExpanded;
    }
}
