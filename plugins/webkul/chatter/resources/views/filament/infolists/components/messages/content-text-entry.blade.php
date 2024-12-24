@php
    $record = $getRecord();
    $changes = is_array($record->properties) ? $record->properties : [];
@endphp

<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $attributes->merge($getExtraAttributes())->class('rounded-lg shadow-md') }}>
        @switch($record->type)
            @case('note')
            @case('comment')
                @if ($record->subject)
                    <div class="mb-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        <span class="block text-gray-500 dark:text-gray-400">Subject:</span>
                        {{ $record->subject }}
                    </div>
                @endif

                @if($record->body)
                    <div class="text-sm">
                        {!! $record->body !!}
                    </div>
                @endif

                @break
            @case('notification')
                @if ($record->body)
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $record->body }}
                    </h3>
                @endif

                @if (
                    count($changes) > 0
                    && $record->event !== 'created'
                )
                    <div class="mt-2 rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10">
                        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <x-heroicon-m-arrow-path class="text-primary-500 h-5 w-5"/>

                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    @lang('chatter::app.views.filament.infolists.components.content-text-entry.changes-made')
                                </h3>
                            </div>
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($changes as $field => $change)
                                @if(is_array($change))
                                    <div class="p-4">
                                        <div class="mb-3 flex items-center gap-2">
                                            @if($field === 'title')
                                                <x-heroicon-m-pencil-square class="h-4 w-4 text-gray-500"/>
                                            @elseif($field === 'due_date')
                                                <x-heroicon-m-calendar class="h-4 w-4 text-gray-500"/>
                                            @else
                                                <x-heroicon-m-arrow-path class="h-4 w-4 text-gray-500"/>
                                            @endif

                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                @lang('chatter::app.views.filament.infolists.components.content-text-entry.modified', [
                                                    'field' => ucwords(str_replace('_', ' ', $field)),
                                                ])

                                                @isset($change['type'])
                                                    <span class="inline-flex items-center rounded-md text-xs">
                                                        {{ ucfirst($change['type']) }}
                                                    </span>
                                                @endisset
                                            </span>
                                        </div>

                                        <div class="mt-2 space-y-2 pl-6">
                                            @if(isset($change['old_value']))
                                                <div class="group flex items-center gap-2">
                                                    <span class="flex-shrink-0">
                                                        <x-heroicon-m-minus-circle
                                                            class="h-4 w-4"
                                                            @style([
                                                                'color: rgb(var(--danger-500))',
                                                            ])
                                                        />
                                                    </span>

                                                    <span
                                                        class="text-sm text-gray-500 transition-colors dark:text-gray-400"
                                                        @style([
                                                            'color: rgb(var(--danger-500))',
                                                        ])
                                                    >
                                                        @if($field === 'due_date')
                                                            {{ \Carbon\Carbon::parse($change['old_value'])->format('F j, Y') }}
                                                        @else
                                                            @if (is_array($change['old_value']))
                                                                {{ implode(', ', $change['old_value']) }}
                                                            @else
                                                                {{ $change['old_value'] }}
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif

                                            @if(isset($change['new_value']))
                                                <div class="group flex items-center gap-2">
                                                    <span class="flex-shrink-0">
                                                        <x-heroicon-m-plus-circle
                                                            class="h-4 w-4 text-green-500"
                                                            @style([
                                                                'color: rgb(var(--success-500))',
                                                            ])
                                                        />
                                                    </span>

                                                    <span class="text-sm font-medium text-gray-900 transition-colors dark:text-gray-100"
                                                            @style([
                                                                'color: rgb(var(--success-500))',
                                                            ])>
                                                        @if($field === 'due_date')
                                                            {{ \Carbon\Carbon::parse($change['new_value'])->format('F j, Y') }}
                                                        @else
                                                            @if (is_array($change['new_value']))
                                                                {{ implode(', ', $change['new_value']) }}
                                                            @else
                                                                {{ $change['new_value'] }}
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @break
            @case('activity')
                <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-heroicon-m-clipboard-document-check class="text-primary-500 h-5 w-5"/>

                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    @lang('chatter::app.views.filament.infolists.components.content-text-entry.activity-details')
                                </h3>
                            </div>

                            <span class="bg-primary-50 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 inline-flex items-center rounded-md px-2 py-1 text-xs font-medium">
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

                                            $deadlineDescription = $deadline->isToday()
                                                ? __('Today')
                                                : ($deadline->isFuture()
                                                    ? ($daysDifference === 1
                                                        ? __('Tomorrow')
                                                        : __('Due in :days days', ['days' => (int)($daysDifference)]))
                                                    : ($daysDifference === -1
                                                        ? __('1 day overdue')
                                                        : __(':days days overdue', ['days' => (int)($daysDifference)]))
                                                );

                                            $textColor = $deadline->isToday()
                                                ? 'color: RGBA(154, 107, 1, var(--text-opacity, 1));'
                                                : ($deadline->isPast()
                                                    ? 'color: RGBA(210, 63, 58, var(--text-opacity, 1));'
                                                    : 'color: RGBA(0, 136, 24, var(--text-opacity, 1));');
                                        @endphp

                                        <span class="text-sm font-bold" @style([$textColor])>
                                            {{ $deadlineDescription }}
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

                @break;
        @endSwitch
    </div>
</x-dynamic-component>
