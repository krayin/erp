<div class="flex h-full w-full flex-col space-y-4">
    <!-- Tabs -->
    <div class="flex justify-between">
        <div class="flex w-full gap-3">
            {{ $this->messageAction($record) }}

            {{ $this->logAction($record) }}

            {{ $this->activityAction($record) }}

            {{ $this->fileAction($record) }}
        </div>

        {{ $this->followerAction($record) }}
    </div>

    <!-- Chats -->
    {{ $this->chatInfolist }}

    <x-filament-actions::modals />
</div>
