<?php

namespace Webkul\Chatter\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;
use Webkul\Security\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Webkul\Chatter\Filament\Actions\Chatter\FollowerActions\AddFollowerAction;

class FollowerPanel extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use WithPagination;
    use InteractsWithActions;

    public Model $record;

    public string $searchQuery = '';

    protected $queryString = ['searchQuery'];

    protected $listeners = ['refreshFollowers' => '$refresh'];

    protected $updatesQueryString = [
        'searchQuery' => ['except' => ''],
    ];

    public function mount(Model $record): void
    {
        $this->record = $record;
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function removeFollower($userId)
    {
        $user = User::findOrFail($userId);

        $this->record->removeFollower($user);
    }

    public function addFollowerAction(): AddFollowerAction
    {
        return AddFollowerAction::make('addFollower')
            ->record($this->record);
    }

    public function render(): View
    {

        return view('chatter::livewire.follower-panel', [
            'followers' => $this->record->followers()->paginate(10),
            'nonFollowers' => User::whereNotIn('id', $this->record->followers()->pluck('user_id')->toArray())->paginate(10),
        ]);
    }
}
