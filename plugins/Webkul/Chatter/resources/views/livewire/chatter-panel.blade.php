<div class="flex h-full flex-col space-y-4">
    <!-- Tabs -->
    <div class="w-full">
        <x-filament::tabs>
            <x-filament::tabs.item
                :active="$activeTab === 'message'"
                wire:click="$set('activeTab', 'message')"
                icon="heroicon-o-chat-bubble-oval-left-ellipsis"
                badge="{{ $this->record->getLatestChats()->count() }}"
            >
                Send
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'log'"
                wire:click="$set('activeTab', 'log')"
                icon="heroicon-o-chat-bubble-oval-left"
                badge="{{ $this->record->chats()->where('notified', 0)->count() }}"
            >
                Log
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'activity'"
                wire:click="$set('activeTab', 'activity')"
                icon="heroicon-o-chat-bubble-oval-left"
                badge="{{ $this->record->chats()->where('notified', 0)->count() }}"
            >
                Activity
            </x-filament::tabs.item>

            <div class="ml-auto flex items-center">
                {{ $this->followerAction }}

                <x-filament-actions::modals />
            </div>
        </x-filament::tabs>
    </div>

    @if($activeTab === 'message')
        <div class="space-y-4">
            {{ $this->createMessageForm }}

            <x-filament::button
                wire:click="create"
                icon="heroicon-m-paper-airplane"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2"
            >
                Send Message
            </x-filament::button>
        </div>

        <x-filament::grid class="gap-4">
            @foreach ($this->record->getLatestChats() as $chat)
                <div
                    class="block break-all rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
                    style="word-break: break-all;"
                >
                    <div class="flex gap-x-3">
                        <x-filament-panels::avatar.user
                            size="md"
                            :user="$chat->user"
                        />

                        <div class="flex-grow space-y-2 pt-[6px]">
                            <div class="flex items-center justify-between gap-x-2">
                                <div class="flex items-center gap-x-2">
                                    <div class="text-sm font-medium text-gray-950 dark:text-white">
                                        {{ $chat->user->name }}
                                    </div>

                                    <div class="text-xs font-medium text-gray-400 dark:text-gray-500">
                                        {{ $chat->created_at->diffForHumans() }}
                                    </div>

                                    @if($chat->notified)
                                        <span class="flex items-center justify-center gap-0.5 text-xs">
                                            <x-filament::icon
                                                icon="heroicon-m-check-circle"
                                                class="mr-1 h-3 w-3"
                                            />

                                            Notified
                                        </span>
                                    @endif
                                </div>

                                {{-- TODO: Handle when role and permission will done. --}}
                                @if (true)
                                    <div class="flex-shrink-0">
                                        <x-filament::icon-button
                                            wire:click="delete({{ $chat->id }})"
                                            icon="heroicon-s-trash"
                                            color="danger"
                                            tooltip="Delete comment"
                                        />
                                    </div>
                                @endif
                            </div>

                            <div class="prose dark:prose-invert [&>*]:mb-2 [&>*]:mt-0 [&>*:last-child]:mb-0 prose-sm text-sm leading-6 text-gray-950 dark:text-white">
                                {{ Str::of($chat->content)->toHtmlString() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </x-filament::grid>
    @endif

    @if($activeTab === 'log')
        <div class="space-y-4">
            {{ $this->createLogForm }}

            <x-filament::button
                wire:click="create"
                icon="heroicon-m-paper-airplane"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2"
            >
                Send Message
            </x-filament::button>
        </div>

        <x-filament::grid class="gap-4">
            @foreach ($this->record->getLatestLog() as $chat)
                <div
                    class="block break-all rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
                    style="word-break: break-all;"
                >
                    <div class="flex gap-x-3">
                        <x-filament-panels::avatar.user
                            size="md"
                            :user="$chat->user"
                        />

                        <div class="flex-grow space-y-2 pt-[6px]">
                            <div class="flex items-center justify-between gap-x-2">
                                <div class="flex items-center gap-x-2">
                                    <div class="text-sm font-medium text-gray-950 dark:text-white">
                                        {{ $chat->user->name }}
                                    </div>

                                    <div class="text-xs font-medium text-gray-400 dark:text-gray-500">
                                        {{ $chat->created_at->diffForHumans() }}
                                    </div>

                                    @if($chat->notified)
                                        <span class="flex items-center justify-center gap-0.5 text-xs">
                                            <x-filament::icon
                                                icon="heroicon-m-check-circle"
                                                class="mr-1 h-3 w-3"
                                            />

                                            Notified
                                        </span>
                                    @endif
                                </div>

                                {{-- TODO: Handle when role and permission will done. --}}
                                @if (true)
                                    <div class="flex-shrink-0">
                                        <x-filament::icon-button
                                            wire:click="delete({{ $chat->id }})"
                                            icon="heroicon-s-trash"
                                            color="danger"
                                            tooltip="Delete comment"
                                        />
                                    </div>
                                @endif
                            </div>

                            <div class="prose dark:prose-invert [&>*]:mb-2 [&>*]:mt-0 [&>*:last-child]:mb-0 prose-sm text-sm leading-6 text-gray-950 dark:text-white">
                                {{ Str::of($chat->content)->toHtmlString() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </x-filament::grid>
    @endif

    @if($activeTab === 'activity')
        <div class="space-y-4">
            {{ $this->createScheduleActivityForm }}

            <x-filament::button
                wire:click="create"
                icon="heroicon-m-paper-airplane"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2"
            >
                Send Message
            </x-filament::button>
        </div>
    @endif

    <!-- Add Follower Modal -->
    <div
        x-data
        x-show="$wire.showFollowerModal"
        x-on:keydown.escape.window="$wire.showFollowerModal = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex min-h-screen items-center justify-center p-4">
            <div
                x-show="$wire.showFollowerModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="relative w-full max-w-lg rounded-lg bg-white shadow-2xl"
                @click.outside="$wire.showFollowerModal = false"
            >
                <!-- Header -->
                <div class="flex items-center justify-between border-b p-4">
                    <h2 class="text-xl font-semibold text-gray-900">Add Followers</h2>
                    <button
                        type="button"
                        wire:click="$toggle('showFollowerModal')"
                        class="rounded-full p-1.5 transition-colors hover:bg-gray-100"
                    >
                        <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Search -->
                <div class="border-b p-4">
                    <input
                        type="text"
                        wire:model.debounce.300ms="searchQuery"
                        placeholder="Search users..."
                        class="w-full rounded-md border border-gray-300 py-2 pl-8 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >
                </div>

                <!-- User List -->
                <div class="max-h-[400px] overflow-y-auto p-2">
                    <div wire:loading.delay class="flex items-center justify-center py-8">
                        <svg class="h-8 w-8 animate-spin text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <div wire:loading.remove>
                        @forelse($this->nonFollowers as $user)
                            <div class="group flex items-center justify-between rounded-lg p-2 transition-colors hover:bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img
                                            src="{{ $user->profile_photo_url ?? 'https://static.thenounproject.com/png/5034901-200.png' }}"
                                            alt="{{ $user->name }}"
                                            class="h-10 w-10 rounded-full object-cover"
                                        >
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ '@' . Str::slug($user->name, '') }}</p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    wire:click="toggleFollower({{ $user->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="toggleFollower({{ $user->id }})"
                                    class="inline-flex items-center gap-1 rounded-full bg-blue-500 px-3 py-1.5 text-sm font-medium transition-colors hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    <svg wire:loading.remove wire:target="toggleFollower({{ $user->id }})" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <svg wire:loading wire:target="toggleFollower({{ $user->id }})" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Follow</span>
                                </button>
                            </div>
                        @empty
                            <div class="m-4 rounded-md bg-yellow-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">No users found</h3>
                                        <p class="mt-2 text-sm text-yellow-700">Try adjusting your search terms.</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
