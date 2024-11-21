<div class="flex h-full w-full flex-col space-y-4">
    <!-- Tabs -->
    <div class="w-full">
        <x-filament::tabs>
            <!-- Message Tab -->
            <x-filament::tabs.item
                :active="$activeTab === 'message'"
                wire:click="toggleTab('message')"
                icon="heroicon-o-chat-bubble-oval-left-ellipsis"
                :badge="$this->record->chats()->where('type', 'message')->count()"
            >
                Message
            </x-filament::tabs.item>

            <!-- Log Tab -->
            <x-filament::tabs.item
                :active="$activeTab === 'log'"
                wire:click="toggleTab('log')"
                icon="heroicon-o-chat-bubble-oval-left"
                :badge="$this->record->chats()->where('type', 'log')->count()"
            >
                Log
            </x-filament::tabs.item>

            <!-- Activity Tab -->
            <x-filament::tabs.item
                :active="$activeTab === 'activity'"
                wire:click="toggleTab('activity')"
                icon="heroicon-o-clock"
            >
                Activity
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'file'"
                wire:click="toggleTab('file')"
                icon="heroicon-o-document-text"
            >
                File
            </x-filament::tabs.item>

            <!-- Right Aligned Actions -->
            <div class="ml-auto flex items-center">
                {{ $this->followerAction }}

                <x-filament-actions::modals />
            </div>
        </x-filament::tabs>
    </div>

    <!-- Tab Content -->
    <div class="w-full flex-grow">
        @if($activeTab === 'message')
            <div class="w-full space-y-4">
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
        @endif

        @if($activeTab === 'log')
            <div class="w-full space-y-4">
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
        @endif

        @if($activeTab === 'activity')
            <div class="w-full space-y-4">
                {{ $this->createScheduleActivityForm }}

                <x-filament::button
                    wire:click="create"
                    icon="heroicon-m-paper-airplane"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2"
                >
                    Save Activity
                </x-filament::button>
            </div>
        @endif

        @if($activeTab === 'file')
            <div class="w-full space-y-4">
                {{ $this->createFileForm }}

                <x-filament::button
                    wire:click="create"
                    icon="heroicon-m-paper-airplane"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2"
                >
                    Add File
                </x-filament::button>
            </div>
        @endif
    </div>

    <!-- Responsive Grid -->
    <x-filament::grid class="w-full gap-4">
        @if ($this->record->activities->isNotEmpty())
            <div class="flex w-full items-center">
                <hr class="flex-grow border-gray-300">

                <span class="px-4 text-sm text-gray-500">Planned Activities</span>

                <hr class="flex-grow border-gray-300">
            </div>
        @endif

        @foreach ($this->record->activities as $activity)
            <div
                class="block w-full rounded-xl p-4 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10"
                style="word-break: break-word; background-color: #f9fafb;"
            >
                <div class="flex gap-x-4">
                    {{-- User Avatar --}}
                    <x-filament-panels::avatar.user
                        size="md"
                        :user="$activity->user"
                    />

                    <div class="flex-grow space-y-3 pt-[6px]">
                        {{-- User Info and Actions --}}
                        <div class="flex items-center justify-between gap-x-4">
                            <div class="flex items-center gap-x-3">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $activity->user->name }}
                                </div>

                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="flex gap-x-2">
                                <x-filament::icon-button
                                    wire:click="deleteChat({{ $activity->id }})"
                                    icon="heroicon-s-trash"
                                    color="danger"
                                    tooltip="Delete Activity"
                                />
                            </div>
                        </div>

                        <div class="prose dark:prose-invert prose-sm text-sm leading-6 text-gray-800 dark:text-gray-300">
                            {{ Str::of($activity->content)->toHtmlString() }}
                        </div>

                        <div class="mt-3 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                            @if ($activity->type)
                                <div>
                                    <strong>Type:</strong> {{ $activity->type }}
                                </div>
                            @endif

                            @if ($activity->summary)
                                <div>
                                    <strong>Summary:</strong> {{ $activity->summary }}
                                </div>
                            @endif

                            @if ($activity->due_date)
                                <div>
                                    <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($activity->due_date)->format('F j, Y') }}
                                </div>
                            @endif

                            @if ($activity->assigned_to)
                                <div>
                                    <strong>Assigned To:</strong> {{ $activity->assignedTo->name ?? 'Unassigned' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @php
            $chatsGroupedByDate = $this->record->chats->groupBy(fn ($chat) => $chat->created_at->isToday() ? 'Today' : $chat->created_at->format('F j, Y'));
        @endphp

        @forelse ($chatsGroupedByDate as $date => $chats)
            <div class="flex w-full items-center">
                <hr class="flex-grow border-gray-300">

                <span class="px-4 text-sm text-gray-500">{{ $date }}</span>

                <hr class="flex-grow border-gray-300">
            </div>

            @foreach ($chats as $chat)
                <div
                    class="block w-full rounded-xl p-4 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 {{ $chat->type === 'log' ? 'bg-gray-100 dark:bg-gray-800' : 'bg-white dark:bg-white/5' }}"
                    style="word-break: break-word;"
                >
                    @if ($chat->type === 'file')
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-3">
                                <x-filament-panels::avatar.user
                                    size="md"
                                    :user="$chat->user"
                                />

                                <div class="flex items-center justify-between gap-x-2">
                                    <div class="flex items-center gap-x-2">
                                        <div class="text-sm font-medium text-gray-950 dark:text-white">
                                            {{ $chat->user->name }}
                                        </div>

                                        <div class="text-xs font-medium text-gray-400 dark:text-gray-500">
                                            {{ $chat->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach($chat->attachments as $attachment)
                                <div class="flex-grow space-y-2 pt-[6px]">
                                    <a
                                        href="{{ $attachment->url }}"
                                        target="_blank"
                                        download="{{ $attachment->original_file_name }}"
                                        class="flex items-center gap-3 space-x-3 rounded-md p-2 transition-colors hover:bg-gray-100"
                                    >
                                        <div class="flex items-center justify-center rounded-md bg-gray-200 p-2">
                                            <x-filament::icon
                                                icon="heroicon-m-document"
                                                class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                            />
                                        </div>

                                        <div>
                                            <p class="max-w-[200px] truncate font-medium text-gray-800">{{ $attachment->original_file_name }}</p>

                                            <p class="max-w-[200px] truncate text-sm text-gray-500">{{ $attachment->size }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
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
                                    </div>

                                    @if (true)
                                        <div class="flex-shrink-0">
                                            <x-filament::icon-button
                                                wire:click="deleteChat({{ $chat->id }})"
                                                icon="heroicon-s-trash"
                                                color="danger"
                                                tooltip="Delete comment"
                                            />
                                        </div>
                                    @endif
                                </div>

                                <div class="prose dark:prose-invert prose-sm text-sm leading-6 text-gray-950 dark:text-white">
                                    {{ Str::of($chat->content)->toHtmlString() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @empty
            <div class="w-full text-center text-gray-400 dark:text-gray-500">
                No comments yet.
            </div>
        @endforelse
    </x-filament::grid>
</div>
