@props([
    'actions',
    'favoriteViews' => [],
    'savedViews' => [],
    'presetViews' => [],
])
@php
    $tableViewsTriggerAction = $this->getTableViewsTriggerAction();
    $savedFilterActions = $this->getTableViewActions();
    $activeTableViewsCount = $this->getActiveTableViewsCount();
    $tableViewsLayout = $this->getTableViewsLayout();
    $tableViewsFormMaxHeight = $this->getTableViewsFormMaxHeight();
    $tableViewsFormWidth = $this->getTableViewsFormWidth();
@endphp

<div {{ $attributes->class(['fi-ta-filters grid gap-y-4']) }}>
    <div class="flex items-center justify-between">
        <h4
            class="text-base font-semibold leading-6 text-gray-950 dark:text-white"
        >
            Views
        </h4>

        <div>
            <x-filament::link
                :attributes="
                    \Filament\Support\prepare_inherited_attributes(
                        new \Illuminate\View\ComponentAttributeBag([
                            'color' => 'danger',
                            'tag' => 'button',
                            'wire:click' => 'resetTableViews',
                            'wire:loading.remove.delay.' . config('filament.livewire_loading_delay', 'default') => '',
                            'wire:target' => 'resetTableViews',
                        ])
                    )
                "
            >
                {{ __('filament-tables::table.filters.actions.reset.label') }}
            </x-filament::link>

            <x-filament::loading-indicator
                :attributes="
                    \Filament\Support\prepare_inherited_attributes(
                        new \Illuminate\View\ComponentAttributeBag([
                            'wire:loading.delay.' . config('filament.livewire_loading_delay', 'default') => '',
                            'wire:target' => 'tableFilters,applyTableFilters,resetTableFiltersForm',
                        ])
                    )->class(['h-5 w-5 text-gray-400 dark:text-gray-500'])
                "
            />
        </div>
    </div>

    <div class="flex flex-col gap-y-6">
        @foreach ([
            'User Favorites' => $favoriteViews,
            'Saved Views' => $savedViews,
            'Preset Views' => $presetViews,
        ] as $label => $views)
            <div class="flex flex-col">
                <div class="flex items-center justify-between min-h-[36px]" style="min-height: 36px">
                    <h3 class="font-medium text-gray-400 dark:text-gray-500">
                        {{ $label }}
                    </h3>
                </div>

                <div class="flex flex-col gap-y-1">
                    @foreach ($views as $view)
                        <div class="flex items-center justify-between px-3 py-1 -mx-3 gap-x-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/5 hover:rounded-lg">
                            <div class="flex w-full gap-x-2 truncate justify-between items-center">
                                <div class="flex flex-1 h-9 items-center truncate">
                                    <div class="flex w-full items-center gap-x-3 truncate">
                                        <x-filament::icon
                                            :icon="$view->getIcon()"
                                            class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                        />
                                        
                                        <div class="flex items-center gap-x-2 truncate" style="">
                                            <span class="truncate">
                                                {{ $view->getLabel() }}
                                            </span>

                                            <span class="text-primary-500">•</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{ $actions }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <x-filament-actions::modals />
    </div>
</div>
