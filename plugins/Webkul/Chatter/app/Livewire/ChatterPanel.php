<?php

namespace Webkul\Chatter\Livewire;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Webkul\Chatter\Models\Task;

class ChatterPanel extends Component implements HasForms
{
    use InteractsWithForms;

    public Model $record;

    public ?array $data = [];

    public function mount(): void
    {
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
                                // Forms\Components\RichEditor::make('content')
                                //     ->hiddenLabel()
                                //     ->required(),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $task = Task::find(1);

        $task->addChat($data['content'], auth()->user()->id);

        Notification::make()
            ->title('Message send successfully.')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('chatter::livewire.chatter-panel');
    }
}
