<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div class="flex items-center justify-center gap-x-3">
        <x-filament-panels::avatar.user
            size="md"
            :user="$getRecord()->user"
            class="cursor-pointer"
        />

        <div class="flex-grow space-y-2 pt-[6px]">
            <div class="flex items-center justify-between gap-x-2">
                <div class="flex items-center gap-x-2">
                    <div class="cursor-pointer text-sm font-medium text-gray-950 dark:text-white">
                        {{ $getRecord()->createdBy->name }}
                    </div>

                    <div class="text-xs font-medium text-gray-400 dark:text-gray-500">
                        {{ $getRecord()->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <x-filament-actions::group
                        size="md"
                        tooltip="More actions"
                        dropdown-placement="bottom-start"
                        :actions="[
                            ($this->markAsDoneAction)(['id' => $getRecord()->id]),
                            ($this->editActivity)(['id' => $getRecord()->id]),
                            ($this->cancelActivity)(['id' => $getRecord()->id]),
                        ]"
                    />
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
