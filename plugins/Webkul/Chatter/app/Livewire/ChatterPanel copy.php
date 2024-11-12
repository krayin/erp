<?php

namespace Webkul\Chatter\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\View\View;

class ChatterPanel extends Component implements HasForms
{
    use WithFileUploads, InteractsWithForms;

    public $activeTab = 'send';
    public $message;
    public $selectedFollowers = [];
    public $attachments = [];
    public $logEntry;
    public $activity;
    public $files = [];
    public $users;
    public Model $record;

    protected $rules = [
        'message' => 'required_if:activeTab,send',
        'selectedFollowers' => 'required_if:activeTab,send|array',
        'logEntry' => 'required_if:activeTab,log',
        'activity' => 'required_if:activeTab,activity',
        'files.*' => 'required_if:activeTab,file|file|max:10240', // 10MB max
    ];

    public function mount()
    {
        $this->users = User::all();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('message')
                    ->label('Message')
                    ->hiddenLabel()
                    ->required()
                    ->when(fn () => $this->activeTab === 'send'),
            ])
            ->statePath('data');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    public function send()
    {
        $this->validate();

        // Store communication
        $communication = Communication::create([
            'type' => 'message',
            'content' => $this->message,
            'user_id' => auth()->id(),
        ]);

        // Send emails to selected followers
        foreach ($this->selectedFollowers as $followerId) {
            $follower = User::find($followerId);
            Mail::to($follower->email)->queue(new CommunicationMail($communication));
        }

        $this->reset(['message', 'selectedFollowers']);
        $this->emit('sent', 'Message sent successfully!');
    }

    public function saveLog()
    {
        $this->validate();

        Communication::create([
            'type' => 'log',
            'content' => $this->logEntry,
            'user_id' => auth()->id(),
        ]);

        $this->reset('logEntry');
        $this->emit('logged', 'Log entry saved!');
    }

    public function saveActivity()
    {
        $this->validate();

        Communication::create([
            'type' => 'activity',
            'content' => $this->activity,
            'user_id' => auth()->id(),
        ]);

        $this->reset('activity');
        $this->emit('activity-saved', 'Activity recorded!');
    }

    public function uploadFiles()
    {
        $this->validate();

        foreach ($this->files as $file) {
            $path = $file->store('communications');
            
            Communication::create([
                'type' => 'file',
                'content' => $path,
                'user_id' => auth()->id(),
            ]);
        }

        $this->reset('files');
        $this->emit('files-uploaded', 'Files uploaded successfully!');
    }

    public function render(): View
    {
        return view('livewire.chatter-panel');
    }
}
