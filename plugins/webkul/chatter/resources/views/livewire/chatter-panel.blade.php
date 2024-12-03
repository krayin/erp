<div class="flex h-full w-full flex-col space-y-4">
    <!-- Actions -->
    <div class="flex justify-between">
        <div class="flex w-full gap-3">
            {{ $this->messageAction }}

            {{ $this->logAction }}

            {{ $this->activityAction }}

            {{ $this->fileAction }}
        </div>

        {{ $this->followerAction }}
    </div>

    <!-- Chats -->
    {{ $this->chatInfolist }}

    <x-filament-actions::modals />
</div>
