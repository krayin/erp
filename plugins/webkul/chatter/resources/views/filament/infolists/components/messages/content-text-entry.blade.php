@php
    $record = $getRecord();
    $changes = is_array($record->changes) ? $record->changes : [];
@endphp

<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $attributes->merge($getExtraAttributes())->class('rounded-lg shadow-md') }}>
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
    </div>
</x-dynamic-component>
