<div class="flex h-full flex-col space-y-4">
    <!-- Followers Section -->
    <div class="rounded-xl bg-white p-4 shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <!-- Followers Count -->
            <h2 class="text-lg font-semibold text-gray-800">Followers ({{ $this->followers->count() }})</h2>

            <!-- Followers Toggle Modal -->
            <x-filament::button
                wire:click="$toggle('showFollowerModal')"
                icon="heroicon-o-user-plus"
                class="inline-flex items-center gap-1"
            >
                Add Follower
            </x-filament::button>
        </div>

        <div class="flex flex-wrap gap-2">
            <!-- Followers Chips -->
            @forelse($this->followers as $follower)
                <div class="group relative inline-flex items-center rounded-full bg-gray-100 px-3 py-1.5 transition-all duration-200 hover:bg-gray-200">
                    <img
                        src="{{ $follower->profile_photo_url ?? 'https://static.thenounproject.com/png/5034901-200.png' }}"
                        alt="{{ $follower->name }}"
                        class="mr-2 h-6 w-6 rounded-full object-cover ring-2 ring-white"
                    >

                    <span class="text-sm font-medium text-gray-900">{{ $follower->name }}</span>

                    <!-- Remove Follower -->
                    <button
                        wire:click="toggleFollower({{ $follower->id }})"
                        class="ml-2 rounded-full p-1 text-gray-500 hover:bg-gray-300 hover:text-gray-700 group-hover:inline-flex"
                        title="Remove follower"
                    >
                        <x-heroicon-m-x-mark class="h-4 w-4" />
                    </button>
                </div>
            @empty
                <p class="text-sm text-gray-500">No followers yet. Add followers to keep them updated.</p>
            @endforelse
        </div>
    </div>

    <!-- Message Form Section -->
    <div class="space-y-4">
        {{ $this->form }}
         
        <x-filament::button
            wire:click="create"
            icon="heroicon-m-paper-airplane"
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-2"
        >
            <span wire:loading.remove>Send Message</span>
            <span wire:loading>Sending...</span>
        </x-filament::button>
    </div>

    <!-- Messages Section -->
    <div class="rounded-xl bg-white p-4 shadow-lg">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Messages</h2>
            <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-600">
                {{ $this->record->getLatestChats()->count() }} messages
            </span>
        </div>

        <div class="space-y-6">
            @foreach($this->record->getLatestChats() as $chat)
                <div class="group relative">
                    <div class="flex gap-4 rounded-xl bg-gray-50 p-4 transition-all duration-200 hover:bg-gray-100/80">
                        {{-- Profile Section --}}
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ $chat->user->profile_photo_url ?? 'https://static.thenounproject.com/png/5034901-200.png' }}"
                                alt="{{ $chat->user->name }}"
                                class="h-8 w-8 rounded-full object-cover shadow-sm ring-2 ring-white"
                            >
                        </div>

                        {{-- Content Section --}}
                        <div class="min-w-0 flex-1">
                            <div class="mb-1 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        {{ $chat->user->name }}
                                    </h3>
                                    <time class="text-sm text-gray-500" datetime="{{ $chat->created_at->toISOString() }}">
                                        {{ $chat->created_at->format('g:i A') }}
                                    </time>
                                </div>
                                @if($chat->notified)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                        <x-heroicon-m-check-circle class="mr-1 h-3 w-3" />
                                        Notified
                                    </span>
                                @endif
                            </div>
                            <div class="prose mt-2 max-w-none break-words text-sm text-gray-700">
                                {!! $chat->message !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($this->record->getLatestChats()->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <x-heroicon-o-chat-bubble-left-right class="h-16 w-16 text-gray-400" />
                    <h3 class="mt-4 text-sm font-medium text-gray-900">No messages yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start the conversation when you're ready.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Follower Modal -->
    <div
        x-data
        x-show="$wire.showFollowerModal"
        x-on:keydown.escape.window="$wire.showFollowerModal = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div
                x-show="$wire.showFollowerModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            ></div>

            <div
                x-show="$wire.showFollowerModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle"
            >
                <div>
                    <div class="mb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Add Followers</h3>
                        <div class="mt-2">
                            <input
                                type="text"
                                wire:model.debounce.300ms="searchQuery"
                                placeholder="Search users..."
                                class="focus:border-primary-500 focus:ring-primary-500 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                            >
                        </div>
                    </div>

                    <div class="max-h-60 overflow-y-auto">
                        @forelse($this->nonFollowers as $user)
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center">
                                    <img
                                        src="{{ $user->profile_photo_url ?? 'https://via.placeholder.com/40' }}"
                                        alt="{{ $user->name }}"
                                        class="h-10 w-10 rounded-full object-cover"
                                    >
                                    <span class="ml-3 text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                                <button
                                    wire:click="toggleFollower({{ $user->id }})"
                                    class="bg-primary-600 hover:bg-primary-500 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm"
                                >
                                    Add
                                </button>
                            </div>
                        @empty
                            <p class="py-4 text-center text-sm text-gray-500">No users found.</p>
                        @endforelse
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button
                        type="button"
                        wire:click="$toggle('showFollowerModal')"
                        class="inline-flex w-full justify-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-200"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>