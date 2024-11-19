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
use Webkul\Core\Models\User;

class ChatterPanel extends Component implements HasForms
{
    use InteractsWithForms;

    public Model $record;

    public ?array $data = [];

    public bool $showFollowerModal = false;

    public string $searchQuery = '';

    public string $activeTab = 'message';

    protected $listeners = ['refreshFollowers' => '$refresh'];

    public function mount(Model $record): void
    {
        $this->record = $record;
        $this->form->fill();
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Send')
                            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                            ->schema([
                                Forms\Components\RichEditor::make('content')
                                    ->hiddenLabel()
                                    ->placeholder('Type your message here...')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',   
                                        'link',
                                        'orderedList',
                                        'unorderedList',
                                        'undo',
                                        'redo',
                                    ])
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Log')
                            ->icon('heroicon-o-chat-bubble-oval-left')
                            ->schema([
                                // Logs or other components
                            ]),
                        Forms\Components\Tabs\Tab::make('Activity Log')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                // Logs or other components
                            ]),
                    ]),
            ])
            ->statePath('data');
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

    public function create(): void
    {
        $data = $this->form->getState();

        if (empty(trim($data['content']))) {
            return;
        }

        try {
            $chat = $this->record->addChat($data['content'], auth()->id());

            $followers = collect([$this->record->user])
                ->merge($this->followers)
                ->unique('id')
                ->filter(fn($user) => $user->id !== auth()->id());

            foreach ($followers as $follower) {
                Mail::to($follower->email)
                    ->queue(new SendMessage(
                        record: $this->record,
                        content: $data['content'],
                        sender: auth()->user()
                    ));
            }

            $chat->update(['notified' => true]);

            $this->form->fill();

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
