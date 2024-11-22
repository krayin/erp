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
                Log Note
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

    <!-- Chats -->
    {{ $this->chatInfolist }}
</div>
