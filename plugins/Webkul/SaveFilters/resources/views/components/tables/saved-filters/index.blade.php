@props([
    'actions',
])
@php
    $savedFiltersTriggerAction = $this->getSavedFiltersTriggerAction();
    $savedFilterActions = $this->getSavedFilterActions();
    $activeSavedFiltersCount = $this->getActiveSavedFiltersCount();
    $savedFiltersLayout = $this->getSavedFiltersLayout();
    $savedFiltersFormMaxHeight = $this->getSavedFiltersFormMaxHeight();
    $savedFiltersFormWidth = $this->getSavedFiltersFormWidth();
@endphp
<div {{ $attributes->class(['fi-ta-filters grid gap-y-4']) }}>
    <div class="flex items-center justify-between">
        <h4
            class="text-base font-semibold leading-6 text-gray-950 dark:text-white"
        >
            Saved Filters
        </h4>

        <div>
            <x-filament::link
                :attributes="
                    \Filament\Support\prepare_inherited_attributes(
                        new \Illuminate\View\ComponentAttributeBag([
                            'color' => 'danger',
                            'tag' => 'button',
                            'wire:click' => 'resetSavedFilters',
                            'wire:loading.remove.delay.' . config('filament.livewire_loading_delay', 'default') => '',
                            'wire:target' => 'resetSavedFilters',
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
</div>
