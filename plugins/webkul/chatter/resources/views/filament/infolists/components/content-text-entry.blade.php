@php
    $record = $getRecord();
    $changes = is_array($record->changes) ? $record->changes : [];
@endphp

<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $attributes->merge($getExtraAttributes())->class(['space-y-4']) }}>
        @if($record->content)
            {!! $record->content !!}
        @endif

        @if($record->attachments->isNotEmpty())
            <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <x-heroicon-m-paper-clip class="text-primary-500 h-5 w-5"/>

                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            Attachments
                        </h3>
                    </div>
                </div>

                <div class="p-4">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        @foreach($record->attachments as $attachment)
                            <a
                                href="{{ $attachment->url }}"
                                target="_blank"
                                download="{{ $attachment->original_file_name }}"
                                class="hover:border-primary-200 dark:hover:border-primary-800 group flex items-center gap-3 rounded-lg border border-gray-200 p-3 transition-all duration-200 dark:border-gray-700 dark:hover:bg-gray-700/50"
                            >
                                <!-- File Icon Container -->
                                <div class="group-hover:bg-primary-50 dark:group-hover:bg-primary-900/50 flex items-center justify-center rounded-lg bg-gray-100 p-2.5 transition-colors dark:bg-gray-800">
                                    <x-heroicon-m-document class="group-hover:text-primary-500 h-5 w-5 text-gray-500 transition-colors dark:text-gray-400"/>
                                </div>

                                <!-- File Details -->
                                <div class="min-w-0 flex-1">
                                    <p class="group-hover:text-primary-600 dark:group-hover:text-primary-400 break-words text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $attachment->original_file_name }}
                                    </p>

                                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                        {{ $attachment->size }}
                                    </p>
                                </div>

                                <!-- Download Icon -->
                                <div class="flex-shrink-0">
                                    <x-heroicon-m-arrow-down-tray
                                        class="group-hover:text-primary-500 h-5 w-5 text-gray-400 transition-colors"
                                    />
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Activity Header Section -->
        @if($record->type == 'activity')
            <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-heroicon-m-clipboard-document-check class="text-primary-500 h-5 w-5"/>

                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                Activity Details
                            </h3>
                        </div>

                        <span class="bg-primary-50 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 inline-flex items-center rounded-md px-2 py-1 text-xs font-medium">
                            {{ ucfirst($record->activity_type) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-3">
                        <!-- Created By -->
                        @if($record->user)
                            <div class="flex items-center gap-3">
                                <x-heroicon-m-user-circle class="h-5 w-5 text-gray-400"/>

                                <div>
                                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Created By</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $record->user->name }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Summary -->
                        @if($record->summary)
                            <div class="flex items-center gap-3">
                                <x-heroicon-m-document class="h-5 w-5 text-gray-400"/>

                                <div>
                                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Summary</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $record->summary }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-3">
                        <!-- Due Date -->
                        @if($record->due_date)
                            <div class="flex items-center gap-3">
                                <x-heroicon-m-calendar class="h-5 w-5 text-gray-400"/>

                                <div>
                                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Due Date</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $record->due_date->format('F j, Y') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Assigned To -->
                        @if($record->assignedTo)
                            <div class="flex items-center gap-3">
                                <x-heroicon-m-user-group class="h-5 w-5 text-gray-400"/>

                                <div>
                                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Assigned To</span>

                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $record->assignedTo->name }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Changes Section -->
        @if(
            !empty($changes)
            && $record->activity_type !== 'created'
        )
            <div
                class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10"
                @style([
                    'background-color: rgba(var(--primary-200), 0.1);' => $record->type == 'note',
                ])
            >
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <x-heroicon-m-arrow-path class="text-primary-500 h-5 w-5"/>

                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            Changes Made
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
                                        The '<b>{{ ucwords(str_replace('_', ' ', $field)) }}</b>' has been

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
        @endif
    </div>
</x-dynamic-component>
