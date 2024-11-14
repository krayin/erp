<?php

namespace Webkul\Chatter\Livewire;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Webkul\Chatter\Models\Message;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;

class ChatterPanel extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms;

    public Model $record;

    public ?array $data = [];

    public array $messages = [];

    public function mount(): void
    {
        $this->form->fill([]);

        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        $this->messages = Message::with('user')
            ->where('task_id', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($message) {
                return [
                    'id'        => $message->id,
                    'message'   => $message->message,
                    'createdAt' => $message->created_at->diffForHumans(),
                    'user'      => [
                        'name'   => $message->user->name,
                        'avatar' => $message->user->avatar_url ?? null,
                    ],
                ];
            })
            ->toArray();
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Send')
                            ->id('send')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->schema([
                                Forms\Components\MarkdownEditor::make('message')
                                    ->label('Message')
                                    ->hiddenLabel()
                                    ->required()
                                    ->reactive()
                            ]),
                        Forms\Components\Tabs\Tab::make('Log')
                            ->id('log')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\MarkdownEditor::make('log_content')
                                    ->label('Log')
                                    ->hiddenLabel()
                                    ->reactive()
                            ]),
                    ])
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $formData = $this->form->getState();
        
        Message::create([
            'user_id' => auth()->user()->id,
            'task_id' => 1,
            'message' => $formData['message'],
        ]);

        $this->form->fill([]);

        $this->loadMessages();

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('chatter::livewire.chatter-panel', [
            'messages' => $this->messages,
        ]);
    }
}
