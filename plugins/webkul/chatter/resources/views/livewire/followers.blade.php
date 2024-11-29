<div class="w-full">
    <!-- Search with loading states -->
    <div class="relative border-b p-4">
        <input
            type="text"
            wire:model.live.debounce.300ms="searchQuery"
            placeholder="Search users to add as followers..."
            class="w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        >

        <!-- Loading indicator -->
        <div wire:loading class="absolute right-6 top-1/2 -translate-y-1/2">
            <x-filament::loading-indicator class="h-4 w-4" />
        </div>
    </div>

    <!-- Current Followers -->
    <div class="p-4">
        <h3 class="mb-3 text-sm font-medium text-gray-900">Current Followers</h3>
        <div class="flex flex-wrap gap-2">
            @forelse($followers as $follower)
                <div
                    wire:key="follower-{{ $follower->id }}"
                    class="group relative inline-flex items-center break-words rounded-full bg-gray-100 px-3 py-1.5 transition-all duration-200 hover:bg-gray-200"
                >
                    <div class="flex items-center gap-2">
                        <x-filament-panels::avatar.user
                            size="sm"
                            :user="$follower"
                        />
                        <span class="text-sm font-medium text-gray-900">{{ $follower->name }}</span>
                    </div>

                    <button
                        type="button"
                        wire:click="toggleFollower({{ $follower->id }})"
                        wire:loading.attr="disabled"
                        class="ml-2 text-gray-400 hover:text-red-500"
                    >
                        <div wire:loading.remove wire:target="toggleFollower({{ $follower->id }})">
                            <x-heroicon-s-x-mark class="h-4 w-4" />
                        </div>
                        <div wire:loading wire:target="toggleFollower({{ $follower->id }})">
                            <x-filament::loading-indicator class="h-4 w-4" />
                        </div>
                    </button>
                </div>
            @empty
                <p class="text-sm text-gray-500">No followers yet.</p>
            @endforelse
        </div>
    </div>

    @if(strlen($searchQuery) > 0)
        <!-- Available Users -->
        <div class="max-h-[400px] overflow-y-auto border-t p-4">
            <h3 class="mb-3 text-sm font-medium text-gray-900">Add Followers</h3>

            <div>
                @if($nonFollowers->count() > 0)
                    @foreach($nonFollowers as $user)
                        <div
                            wire:key="non-follower-{{ $user->id }}"
                            class="group flex items-center justify-between rounded-lg p-2 transition-colors hover:bg-gray-50"
                        >
                            <div class="flex items-center gap-3">
                                <x-filament-panels::avatar.user
                                    size="md"
                                    :user="$user"
                                />
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>

                            <x-filament::button
                                type="button"
                                wire:click="toggleFollower({{ $user->id }})"
                                wire:loading.attr="disabled"
                                size="sm"
                            >
                                <span wire:loading.remove wire:target="toggleFollower({{ $user->id }})">
                                    Add
                                </span>
                                <span wire:loading wire:target="toggleFollower({{ $user->id }})">
                                    Adding...
                                </span>
                            </x-filament::button>
                        </div>
                    @endforeach

                    @if($nonFollowers->hasPages())
                        <div class="mt-4">
                            {{ $nonFollowers->links() }}
                        </div>
                    @endif
                @else
                    <div class="py-4 text-center">
                        <p class="text-gray-500">No users found matching "{{ $searchQuery }}".</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
