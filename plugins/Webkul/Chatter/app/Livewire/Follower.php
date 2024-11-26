<?php

namespace Webkul\Chatter\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;
use Webkul\Core\Models\User;

class Follower extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    public Model $record;

    public string $searchQuery = '';

    protected $queryString = ['searchQuery'];

    protected $listeners = ['refreshFollowers' => '$refresh'];

    protected $updatesQueryString = [
        'searchQuery' => ['except' => '']
    ];

    public function mount(Model $record): void
    {
        $this->record = $record;
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
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
        if (empty($this->searchQuery)) {
            return collect();
        }

        $followerIds = $this->record->followers()
            ->select('users.id')
            ->pluck('users.id')
            ->toArray();

        return User::query()
            ->whereNotIn('users.id', array_merge($followerIds, [$this->record->created_by]))
            ->where(function ($query) {
                $query->where('users.name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('users.email', 'like', '%' . $this->searchQuery . '%');
            })
            ->orderBy('name')
            ->paginate(10);
    }

    public function toggleFollower($userId): void
    {
        try {
            $user = User::findOrFail($userId);

            if ($this->record->isFollowedBy($userId)) {
                $this->record->removeFollower($userId);
                $message = "Successfully removed {$user->name} as a follower.";
            } else {
                $this->record->addFollower($userId);
                $message = "Successfully added {$user->name} as a follower.";
            }

            $this->dispatch('refreshFollowers');

            Notification::make()
                ->title($message)
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error managing follower')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function render(): View
    {
        return view('chatter::livewire.followers', [
            'followers' => $this->getFollowersProperty(),
            'nonFollowers' => $this->getNonFollowersProperty(),
        ]);
    }
}
