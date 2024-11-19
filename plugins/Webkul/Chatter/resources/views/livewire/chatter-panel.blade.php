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
                        <!-- Profile Section -->
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ $chat->user->profile_photo_url ?? 'https://static.thenounproject.com/png/5034901-200.png' }}"
                                alt="{{ $chat->user->name }}"
                                class="h-8 w-8 rounded-full object-cover shadow-sm ring-2 ring-white"
                            >
                        </div>

                        <!-- Content Section -->
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

                            <!-- Message Section -->
                            <div class="prose mt-2 max-w-none break-words text-sm text-gray-700">
                                {!! $chat->message !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- No Message Section -->
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

                <!-- Footer -->
                <div class="border-t p-4">
                    <button
                        wire:click="$toggle('showFollowerModal')"
                        class="w-full rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 transition-colors hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                    >
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>