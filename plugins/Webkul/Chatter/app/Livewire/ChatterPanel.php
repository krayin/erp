<?php

namespace Webkul\Chatter\Livewire;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Webkul\Chatter\Mail\SendMessage;

class ChatterPanel extends Component implements HasForms
{
    use InteractsWithForms;

    public Model $record;

    public ?array $data = [];

    public function mount(Model $record): void
    {
        $this->record = $record;

        $this->form->fill([]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Send')
                            ->schema([
                                Forms\Components\RichEditor::make('content')
                                    ->hiddenLabel()
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Log')
                            ->schema([
                                // Logs or other components
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $chat = $this->record->addChat($data['content'], auth()->user()->id);

        try {
            Mail::to($this->record->user->email)
                ->send(new SendMessage($this->record, $data['content']));

            $chat->update(['notified' => true]);

            Notification::make()
                ->title('Message sent successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to send message.')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        $this->form->fill([]);
    }

    public function render(): View
    {
        return view('chatter::livewire.chatter-panel');
    }
}
