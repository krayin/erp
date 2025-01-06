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
                        <span class="block text-gray-500 dark:text-gray-400">
                            @lang('chatter::views/filament/infolists/components/messages/content-text-entry.subject'):
                        </span>
                        {{ $record->subject }}
                    </div>
                @endif

                @if($record->body)
                    <div class="text-sm">
                        {!! $record->body !!}
                    </div>
                @endif

                <section class="mt-2 overflow-hidden text-gray-700">
                    <div class="container mx-auto px-5 py-2 lg:px-32 lg:pt-24">
                        <div class="-m-1 flex flex-wrap md:-m-2">
                            @foreach($record->attachments->chunk(4) as $chunk)
                                <div class="grid gap-2">
                                    @foreach($chunk as $attachment)
                                        @php
                                            $fileExtension = strtolower(pathinfo($attachment->original_file_name, PATHINFO_EXTENSION));

                                            switch($fileExtension) {
                                                case 'pdf':
                                                    $icon = 'heroicon-o-document-text';
                                                    break;
                                                case 'sql':
                                                    $icon = 'heroicon-o-database';
                                                    break;
                                                case 'csv':
                                                    $icon = 'heroicon-o-table-cells';
                                                    break;
                                                case 'md':
                                                    $icon = 'heroicon-o-document';
                                                    break;
                                                default:
                                                    $icon = 'heroicon-o-document';
                                            }
                                        @endphp

                                        <div class="flex gap-2 rounded-md bg-gray-100 px-3 py-2">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-md">
                                                <x-filament::icon
                                                    :icon="$icon"
                                                    class="h-5 w-5"
                                                />
                                            </div>

                                            <div class="flex flex-col gap-2">
                                                <div class="flex flex-1 flex-col">
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $attachment->original_file_name }}
                                                    </span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    @if(in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                                        <x-filament::button
                                                            size="xs"
                                                            color="gray"
                                                            icon="heroicon-m-eye"
                                                            class="!gap-0"
                                                            icon-only
                                                            tag="a"
                                                            :href="Storage::url($attachment->file_path)"
                                                            target="_blank"
                                                            :tooltip="__('chatter::views/filament/infolists/components/messages/content-text-entry.preview')"
                                                        />
                                                    @endif

                                                    <x-filament::button
                                                        size="xs"
                                                        color="gray"
                                                        icon="heroicon-m-arrow-down-tray"
                                                        class="!gap-0"
                                                        icon-only
                                                        tag="a"
                                                        :href="Storage::url($attachment->file_path)"
                                                        download="{{ $attachment->original_file_name }}"
                                                        :tooltip="__('chatter::views/filament/infolists/components/messages/content-text-entry.download')"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                @break
            @case('notification')
                @if ($record->body)
                    <h3 class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                        {!! $record->body !!}
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

                                <h3 class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    @lang('chatter::views/filament/infolists/components/messages/content-text-entry.changes-made')
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

                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                @lang('chatter::views/filament/infolists/components/messages/content-text-entry.modified', [
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
                                                                {!! $change['old_value'] !!}
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
                                                                {!! $change['new_value'] !!}
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
        @endSwitch
    </div>
</x-dynamic-component>
