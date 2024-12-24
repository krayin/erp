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
                <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <x-heroicon-m-arrow-path class="text-primary-500 h-5 w-5"/>

                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                @lang('chatter::app.views.filament.infolists.components.content-text-entry.changes-made')
                            </h3>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        {{-- @foreach($changes as $field => $change)
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

                                    <div class="space-y-2 pl-6">
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
                        @endforeach --}}

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
                                            'field' => isset($change['relation'])
                                                ? ucwords(str_replace('_', ' ', $change['relation']))
                                                : ucwords(str_replace('_', ' ', $field)),
                                        ])

                                        @isset($change['type'])
                                            <span class="inline-flex items-center rounded-md text-xs">
                                                {{ ucfirst($change['type']) }}
                                            </span>
                                        @endisset
                                    </span>
                                </div>

                                <div class="space-y-2 pl-6">
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
                @break
        @endSwitch
    </div>
</x-dynamic-component>
