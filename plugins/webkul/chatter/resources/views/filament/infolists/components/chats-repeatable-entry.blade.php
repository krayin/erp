<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div
        {{
            $attributes
                ->merge([
                    'id' => $getId(),
                ], escape: false)
                ->merge($getExtraAttributes(), escape: false)
        }}
    >
        @if (count($childComponentContainers = $getChildComponentContainers()))
            <x-filament::grid
                :default="$getGridColumns('default')"
                :sm="$getGridColumns('sm')"
                :md="$getGridColumns('md')"
                :lg="$getGridColumns('lg')"
                :xl="$getGridColumns('xl')"
                :two-xl="$getGridColumns('2xl')"
                class="gap-2"
            >
                @foreach ($childComponentContainers as $container)
                    <article
                        class="mb-3 rounded-lg border border-gray-200 bg-white p-6 text-base dark:border-gray-700 dark:bg-gray-900"
                        @style([
                            'background-color: rgba(var(--primary-200), 0.1);' => $container->record->type == 'note',
                        ])
                    >
                        {{ $container }}
                    </article>
                @endforeach
            </x-filament::grid>
        @elseif (($placeholder = $getPlaceholder()) !== null)
            <x-filament-infolists::entries.placeholder>
                {{ $placeholder }}
            </x-filament-infolists::entries.placeholder>
        @endif
    </div>
</x-dynamic-component>
