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
                    <x-filament::icon-button
                        wire:click="mountAction('deleteActivity',  { id: {{ $getRecord()->id }} })"
                        icon="heroicon-s-trash"
                        color="danger"
                        :tooltip="trans('chatter::app.views.filament.infolists.components.title-text-entry.tooltip.delete')"
                    />
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
