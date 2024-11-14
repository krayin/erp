<div class="flex flex-col h-full space-y-4">
    <div class="space-y-4">
        {{ $this->form }}
        
        <x-filament::button
            wire:click="create"
            color="primary"
        >
            Send
        </x-filament::button>
    </div>

    <div class="space-y-4">
        {{ $this->messageList }}
    </div>
</div>