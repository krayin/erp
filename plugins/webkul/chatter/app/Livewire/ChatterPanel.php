<?php

namespace Webkul\Chatter\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;
use Webkul\Chatter\Filament\Actions\Chatter\ActivityAction;
use Webkul\Chatter\Filament\Actions\Chatter\FileAction;
use Webkul\Chatter\Filament\Actions\Chatter\FollowerAction;
use Webkul\Chatter\Filament\Actions\Chatter\LogAction;
use Webkul\Chatter\Filament\Actions\Chatter\MessageAction;
use Webkul\Chatter\Filament\Infolists\Components\Activities\ActivitiesRepeatableEntry;
use Webkul\Chatter\Filament\Infolists\Components\Activities\ContentTextEntry as ActivityContentTextEntry;
use Webkul\Chatter\Filament\Infolists\Components\Activities\TitleTextEntry as ActivityTitleTextEntry;
use Webkul\Chatter\Filament\Infolists\Components\Messages\ContentTextEntry as MessageContentTextEntry;
use Webkul\Chatter\Filament\Infolists\Components\Messages\MessageRepeatableEntry;
use Webkul\Chatter\Filament\Infolists\Components\Messages\TitleTextEntry as MessageTitleTextEntry;
use Webkul\Security\Models\User;

class ChatterPanel extends Component implements HasActions, HasForms, HasInfolists
{
    use InteractsWithActions, InteractsWithForms, InteractsWithInfolists, WithFileUploads;

    public Model $record;

    public string $searchQuery = '';

    protected $listeners = ['refreshFollowers' => '$refresh'];

    public function mount(Model $record): void
    {
        $this->record = $record;
    }

    public function messageAction(): MessageAction
    {
        return MessageAction::make('message')
            ->record($this->record);
    }

    public function logAction(): LogAction
    {
        return LogAction::make('log')
            ->record($this->record);
    }

    public function fileAction(): FileAction
    {
        return FileAction::make('file')
            ->record($this->record);
    }

    public function activityAction(): ActivityAction
    {
        return ActivityAction::make('activity')
            ->record($this->record);
    }

    public function deleteMessageAction(): Action
    {
        return Action::make('deleteMessage')
            ->requiresConfirmation()
            ->action(fn (array $arguments) => $this->record->removeMessage($arguments['id']));
    }

    public function deleteActivityAction(): Action
    {
        return Action::make('deleteActivity')
            ->requiresConfirmation()
            ->action(fn (array $arguments) => $this->record->removeMessage($arguments['id'], 'activities'));
    }

    public function deleteAttachmentAction(): Action
    {
        return Action::make('deleteAttachment')
            ->requiresConfirmation()
            ->action(fn (array $arguments) => dd($arguments));
    }

    public function chatInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                MessageRepeatableEntry::make('messages')
                    ->hiddenLabel()
                    ->schema([
                        MessageTitleTextEntry::make('user')
                            ->hiddenLabel(),
                        MessageContentTextEntry::make('content')
                            ->hiddenLabel(),
                    ])
                    ->placeholder(__('chatter::app.livewire.chatter_panel.placeholders.no_record_found')),
            ]);
    }

    public function activityInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema(function () {

                if ($this->record->activities->isEmpty()) {
                    return [];
                }

                return [
                    Section::make('Activities')
                        ->collapsible()
                        ->collapsed()
                        ->compact()
                        ->schema([
                            ActivitiesRepeatableEntry::make('activities')
                                ->hiddenLabel()
                                ->schema([
                                    ActivityTitleTextEntry::make('user')
                                        ->hiddenLabel(),
                                    ActivityContentTextEntry::make('content')
                                        ->hiddenLabel(),
                                ])
                                ->placeholder(__('chatter::app.livewire.chatter_panel.placeholders.no_record_found')),
                        ]),
                ];
            });
    }

    public function placeholder()
    {
        return <<<'HTML'
            <div class="flex w-full items-center justify-center">
                <div class="flex flex-col items-center space-y-4">
                    <x-filament::loading-indicator class="text-primary-500 h-10 w-10 animate-spin" />
                    <p class="text-sm font-medium tracking-wide text-gray-600 dark:text-gray-300">
                        {{ __('chatter::app.livewire.chatter_panel.placeholders.loading') }}
                    </p>
                </div>
            </div>
        HTML;
    }

    public function render(): View
    {
        return view('chatter::livewire.chatter-panel');
    }

    // public function followerAction(): FollowerAction
    // {
    //     return FollowerAction::make('follower')
    //         ->record($this->record);
    // }

    // public function getFollowersProperty()
    // {
    //     return $this->record->followers()
    //         ->select('users.*')
    //         ->orderBy('name')
    //         ->get();
    // }

    // public function getNonFollowersProperty()
    // {
    //     $followerIds = $this->record->followers()
    //         ->select('users.id')
    //         ->pluck('users.id')
    //         ->toArray();

    //     return User::query()
    //         ->whereNotIn('users.id', array_merge($followerIds, [$this->record->user_id]))
    //         ->when($this->searchQuery, function ($query) {
    //             $query->where('users.name', 'like', '%' . $this->searchQuery . '%')
    //                 ->orWhere('users.email', 'like', '%' . $this->searchQuery . '%');
    //         })
    //         ->orderBy('name')
    //         ->limit(50)
    //         ->get();
    // }

    // public function toggleFollower($userId): void
    // {
    //     try {
    //         if ($this->record->isFollowedBy($userId)) {
    //             $this->record->removeFollower($userId);
    //             $message = __('chatter::app.livewire.chatter_panel.actions.follower.remove_success');
    //         } else {
    //             $this->record->addFollower($userId);
    //             $message = __('chatter::app.livewire.chatter_panel.actions.follower.add_success');
    //         }

    //         $this->dispatch('refreshFollowers');

    //         Notification::make()
    //             ->title($message)
    //             ->success()
    //             ->send();
    //     } catch (\Exception $e) {
    //         Notification::make()
    //             ->title(__('chatter::app.livewire.chatter_panel.actions.follower.error'))
    //             ->body($e->getMessage())
    //             ->danger()
    //             ->send();
    //     }
    // }

}
