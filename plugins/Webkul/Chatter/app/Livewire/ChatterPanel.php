<?php

namespace Webkul\Chatter\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Webkul\Chatter\Enums\ActivityType;
use Webkul\Chatter\Filament\Actions\FollowerAction;
use Webkul\Chatter\Mail\SendMessage;
use Webkul\Chatter\Models\Chat;
use Webkul\Core\Models\User;

class ChatterPanel extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    public Model $record;

    public ?array $messageForm = [];

    public ?array $logForm = [];

    public ?array $scheduleActivityForm = [];

    public bool $showFollowerModal = false;

    public string $searchQuery = '';

    public string $activeTab = '';

    protected $listeners = ['refreshFollowers' => '$refresh'];

    public function mount(Model $record): void
    {
        $this->record = $record;

        $this->createMessageForm->fill();

        $this->createLogForm->fill();

        $this->createScheduleActivityForm->fill();
    }

    public function toggleTab(string $tab): void
    {
        $this->activeTab = $this->activeTab === $tab ? '' : $tab;
    }

    public function followerAction(): FollowerAction
    {
        return FollowerAction::make('follower')
            ->record($this->record);
    }

    public function getFollowersProperty()
    {
        return $this->record->followers()
            ->select('users.*')
            ->orderBy('name')
            ->get();
    }

    public function getNonFollowersProperty()
    {
        $followerIds = $this->record->followers()
            ->select('users.id')
            ->pluck('users.id')
            ->toArray();

        return User::query()
            ->whereNotIn('users.id', array_merge($followerIds, [$this->record->user_id]))
            ->when($this->searchQuery, function ($query) {
                $query->where('users.name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('users.email', 'like', '%' . $this->searchQuery . '%');
            })
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    public function toggleFollower($userId): void
    {
        try {
            if ($this->record->isFollowedBy($userId)) {
                $this->record->removeFollower($userId);
                $message = 'Follower removed successfully.';
            } else {
                $this->record->addFollower($userId);
                $message = 'Follower added successfully.';
            }

            $this->dispatch('refreshFollowers');

            Notification::make()
                ->title($message)
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error managing follower.')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function notifyToFollowers($chat): void
    {
        try {
            foreach ($this->followers as $follower) {
                Mail::queue(new SendMessage($this->record, $follower, $chat));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function deleteChat($chatId)
    {
        $chat = Chat::find($chatId);

        if (! $chat) {
            return;
        }

        $chat->delete();

        Notification::make()
            ->title('Chat is deleted successfully.')
            ->success()
            ->send();
    }

    protected function getForms(): array
    {
        return [
            'createMessageForm',
            'createLogForm',
            'createScheduleActivityForm',
        ];
    }

    public function createMessageForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('content')
                    ->hiddenLabel()
                    ->placeholder('Type your message here...')
                    ->required(),
                Forms\Components\Hidden::make('type')
                    ->default('message'),
            ])
            ->statePath('messageForm');
    }


    public function createLogForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('content')
                    ->hiddenLabel()
                    ->placeholder('Type your message here...')
                    ->required(),
                Forms\Components\Hidden::make('type')
                    ->default('log'),
            ])
            ->statePath('logForm');
    }

    public function createScheduleActivityForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Select::make('activity_type')
                            ->label('Activity Type')
                            ->options(ActivityType::options())
                            ->required(),
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Due Date')
                            ->native(false)
                            ->required(),
                    ])->columns(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('summary')
                            ->label('Summary')
                            ->required(),
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned To')
                            ->searchable()
                            ->live()
                            ->options(User::all()->pluck('name', 'id')->toArray())
                            ->required(),
                    ])->columns(2),
                Forms\Components\RichEditor::make('content')
                    ->hiddenLabel()
                    ->placeholder('Type your message here...')
                    ->required(),
                Forms\Components\Hidden::make('type')
                    ->default('activity'),
            ])
            ->statePath('scheduleActivityForm');
    }

    public function getFormType(): string
    {
        if ($this->activeTab === 'message') {
            return 'createMessageForm';
        }

        if ($this->activeTab === 'log') {
            return 'createLogForm';
        }

        if ($this->activeTab === 'activity') {
            return 'createScheduleActivityForm';
        }
    }

    public function create(): void
    {
        $formType = $this->getFormType();

        $data = $this->{$formType}->getState();

        if (empty(trim($data['content']))) {
            return;
        }

        try {
            $chat = $this->record->addChat($data, auth()->id());

            if ($data['type'] === 'message') {
                $this->notifyToFollowers($chat, $data);
            }

            $this->{$formType}->fill();

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

            report($e);
        }
    }

    public function render(): View
    {
        return view('chatter::livewire.chatter-panel');
    }
}
