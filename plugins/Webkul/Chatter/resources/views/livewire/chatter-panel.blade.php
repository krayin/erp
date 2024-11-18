<div class="flex h-full flex-col space-y-4">
    <div class="space-y-4">
        {{ $this->form }}
            
        <x-filament::button
            wire:click="create"
            color="primary"
        >
            Send
        </x-filament::button>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Messages</h2>
            <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-600">
                {{ $this->record->getLatestChats()->count() }} messages
            </span>
        </div>

        <!-- Messages Container -->
        <div class="space-y-6">
            @foreach($this->record->getLatestChats() as $chat)
                <div class="group relative">
                    <div class="flex gap-4 rounded-xl bg-gray-50 p-4 transition-all duration-200 hover:bg-gray-100/80">
                        <!-- Profile Section -->
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ $chat->user->profile_photo_url ?? 'https://via.placeholder.com/48' }}"
                                alt="{{ $chat->user->name }}"
                                class="h-12 w-12 rounded-full object-cover shadow-sm ring-2 ring-white"
                            >
                        </div>

                        <!-- Content Section -->
                        <div class="min-w-0 flex-1">
                            <div class="mb-1 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        {{ $chat->user->name }}
                                    </h3>

                                    <time class="text-sm text-gray-500" datetime="{{ $chat->created_at }}">
                                        {{ $chat->created_at->format('g:i A') }}
                                    </time>
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="mt-2 break-words text-sm text-gray-700">
                                {!! $chat->message !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($this->record->getLatestChats()->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="mt-4 text-sm font-medium text-gray-900">No messages yet</h3>
                <p class="mt-1 text-sm text-gray-500">Start the conversation when you're ready.</p>
            </div>
        @endif
    </div>
</div>