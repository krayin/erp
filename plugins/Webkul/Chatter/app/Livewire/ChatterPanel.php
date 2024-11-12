<?php

namespace Webkul\Chatter\Livewire;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ChatterPanel extends Component implements HasForms
{
    use InteractsWithForms;

    public Model $record;

    public $activeTab = 'send';

    public function mount(): void
    {
        $this->form->fill([]);
    }

    public function form(Form $form): Form
    {
        $components = [];

        if ($this->activeTab === 'send') {
            $components = [
                Forms\Components\TextInput::make('message')
                    ->label('Message')
                    ->hiddenLabel()
                    ->required(),
            ];
        }

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
                                Forms\Components\RichEditor::make('content')
                                    ->hiddenLabel()
                                    ->required(),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function render(): View
    {
        return view('chatter::livewire.chatter-panel');
    }
}
