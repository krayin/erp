<div class="flex h-full w-full flex-col space-y-4">
    <!-- Actions -->
    <div class="flex justify-between">
        <div class="flex w-full justify-between gap-3">
            <div>
                {{ $this->messageAction }}

                {{ $this->logAction }}

                {{ $this->activityAction }}
            </div>

            <div>
                {{ $this->fileAction }}

                {{ $this->followerAction }}
            </div>
        </div>

        {{-- {{ $this->followerAction }} --}}
    </div>

    <!-- Activities -->
    {{ $this->activityInfolist }}

    <!-- Messages -->
    {{ $this->chatInfolist }}

    <x-filament-actions::modals />
</div>
