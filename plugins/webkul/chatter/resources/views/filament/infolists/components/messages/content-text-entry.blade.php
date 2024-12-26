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

                <section class="overflow-hidden text-gray-700">
                    <div class="container mx-auto px-5 py-2 lg:px-32 lg:pt-24">
                        <div class="-m-1 flex flex-wrap md:-m-2">
                            @foreach($record->attachments->chunk(3) as $chunk)
                                <div class="grid w-1/2 grid-cols-3 flex-wrap">
                                    @foreach($chunk as $index => $attachment)
                                        @php
                                            $isImage = str_starts_with($attachment->mime_type, 'image/');
                                            $isPdf = str_contains($attachment->mime_type, 'pdf');
                                            $isDoc = str_contains($attachment->mime_type, 'word') || str_contains($attachment->mime_type, 'document');
                                            $isSpreadsheet = str_contains($attachment->mime_type, 'sheet') || str_contains($attachment->mime_type, 'excel');
                                            $iconColor = match(true) {
                                                $isImage => 'text-warning-500',
                                                $isPdf => 'text-danger-500',
                                                $isDoc => 'text-primary-500',
                                                $isSpreadsheet => 'text-success-500',
                                                default => 'text-gray-500'
                                            };
                                            $icon = match(true) {
                                                $isImage => 'heroicon-o-photo',
                                                $isPdf => 'heroicon-o-document',
                                                $isDoc => 'heroicon-o-document-text',
                                                $isSpreadsheet => 'heroicon-o-table-cells',
                                                default => 'heroicon-o-paper-clip'
                                            };
                                        @endphp

                                        <div class="w-full p-1 md:p-2">
                                            @if($isImage && $attachment->url)
                                                <img
                                                    src="{{ $attachment->url }}"
                                                    alt="{{ $attachment->original_file_name }}"
                                                    class="block h-full w-full rounded-lg object-cover object-center"
                                                >
                                            @else
                                                <div class="flex flex-col items-center justify-center rounded-lg border bg-white p-4 dark:bg-gray-800">
                                                    <div class="flex h-12 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700">
                                                        <x-filament::icon
                                                            :icon="$icon"
                                                            @class([$iconColor, 'w-6 h-6'])
                                                        />
                                                    </div>
                                                    <div class="mt-2 text-center">
                                                        <h5 class="truncate text-sm font-medium text-gray-900 dark:text-gray-200">
                                                            {{ $attachment->original_file_name }}
                                                        </h5>
                                                        @if($isImage || $isPdf)
                                                        <x-filament::button
                                                            size="xs"
                                                            color="gray"
                                                            icon="heroicon-m-eye"
                                                            @style([
                                                                'gap' => '0 !important',
                                                            ])
                                                            icon-only
                                                            tag="a"
                                                            :href="Storage::url($attachment->file_path)"
                                                            target="_blank"
                                                            :tooltip="__('Preview')"
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
                                                        :tooltip="__('Download')"
                                                        @style([
                                                            'gap' => '0 !important',
                                                        ])
                                                    />
                                                    </div>
                                                </div>
                                            @endif
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
        @endSwitch
    </div>
</x-dynamic-component>
