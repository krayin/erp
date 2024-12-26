<div class="flex h-full w-full flex-col space-y-4">
    <!-- Actions -->
    <div class="flex w-full justify-between gap-3">
        <div class="flex gap-3">
            {{ $this->messageAction }}

            {{ $this->logAction }}

            {{ $this->activityAction }}
        </div>

        <div class="flex gap-3">
            {{ $this->fileAction }}

            {{ $this->followerAction }}
        </div>
    </div>


    <!-- Activities -->
    {{ $this->activityInfolist }}

    <!-- Messages -->
    {{ $this->chatInfolist }}

    <x-filament-actions::modals />
</div>
