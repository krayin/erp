@php
    $record = $getRecord();
    $changes = is_array($record->properties) ? $record->properties : [];
@endphp

<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $attributes->merge($getExtraAttributes())->class('rounded-lg shadow-md') }}>
        <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10">
            <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <x-heroicon-m-clipboard-document-check class="text-primary-500 h-5 w-5"/>

                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            @lang('chatter::app.views.filament.infolists.components.content-text-entry.activity-details')
                        </h3>
                    </div>

                    <span class="bg-primary-50 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 inline-flex items-center rounded-md px-2 py-1 text-xs font-bold">
                        {{ ucfirst($record->activityType->name) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2">
                <!-- Left Column -->
                <div class="space-y-3">
                    <!-- Created By -->
                    @if($record->createdBy)
                        <div class="flex items-center gap-3">
                            <x-heroicon-m-user-circle class="h-5 w-5 text-gray-400"/>

                            <div>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">
                                    @lang('chatter::app.views.filament.infolists.components.content-text-entry.created-by')
                                </span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $record->createdBy->name }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Summary -->
                    @if($record->summary)
                        <div class="flex items-center gap-3">
                            <x-heroicon-m-document class="h-5 w-5 text-gray-400"/>

                            <div>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">
                                    @lang('chatter::app.views.filament.infolists.components.content-text-entry.summary')
                                </span>

                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $record->summary }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-3">
                    <!-- Due Date -->
                    @if($record->date_deadline)
                        <div class="flex items-center gap-3 rounded-lg p-2">
                            <x-heroicon-m-calendar class="h-5 w-5 text-gray-400"/>

                            <div>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">
                                    @lang('chatter::app.views.filament.infolists.components.content-text-entry.due-date')
                                </span>

                                @php
                                    $deadline = \Carbon\Carbon::parse($record->date_deadline);
                                    $now = \Carbon\Carbon::now();
                                    $daysDifference = $now->diffInDays($deadline, false);
                                    $roundedDays = ceil(abs($daysDifference));

                                    $deadlineDescription = $deadline->isToday()
                                        ? __('Today')
                                        : ($deadline->isFuture()
                                            ? ($roundedDays === 1
                                                ? __('Tomorrow')
                                                : __('Due in :days days', ['days' => $roundedDays])
                                            )
                                            : ($roundedDays === 1
                                                ? __('1 day overdue')
                                                : __(':days days overdue', ['days' => $roundedDays])
                                            )
                                        );

                                    $textColor = $deadline->isToday()
                                        ? 'color: RGBA(154, 107, 1, var(--text-opacity, 1));'
                                        : ($deadline->isPast()
                                            ? 'color: RGBA(210, 63, 58, var(--text-opacity, 1));'
                                            : 'color: RGBA(0, 136, 24, var(--text-opacity, 1));'
                                        );
                                @endphp

                                <span class="text-sm font-bold" @style([$textColor])>
                                    <div class="flex items-center gap-2">
                                        {{ $deadlineDescription }}

                                        <x-filament::icon-button
                                            icon="heroicon-m-question-mark-circle"
                                            color="gray"
                                            :tooltip="$deadline->format('F j, Y')"
                                            label="New label"
                                        />
                                    </div>
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Assigned To -->
                    @if($record->assignedTo)
                        <div class="flex items-center gap-3">
                            <x-heroicon-m-user-group class="h-5 w-5 text-gray-400"/>

                            <div>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">
                                    @lang('chatter::app.views.filament.infolists.components.content-text-entry.assigned-to')
                                </span>

                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $record->assignedTo->name }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
