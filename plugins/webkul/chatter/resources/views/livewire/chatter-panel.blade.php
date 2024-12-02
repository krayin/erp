<div class="flex h-full w-full flex-col space-y-4">
    <!-- Tabs -->
    <div class="flex w-full gap-2">
        {{ $this->messageAction($record) }}

        {{ $this->logAction($record) }}

        {{ $this->activityAction($record) }}

        {{ $this->fileAction($record) }}

        {{ $this->followerAction($record) }}
    </div>

    <!-- Chats -->
    {{ $this->chatInfolist }}

    <x-filament-actions::modals />
</div>
