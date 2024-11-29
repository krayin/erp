@props([
    'activeTableView',
    'isActiveTableViewModified',
    'favoriteViews' => [],
    'savedViews' => [],
    'presetViews' => [],
])

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
            'Favorites Views' => $favoriteViews,
            'Saved Views' => $savedViews,
            'Preset Views' => $presetViews,
        ] as $label => $views)
            @if (empty($views))
                @continue
            @endif
            
            <div class="flex flex-col">
                <div class="flex items-center justify-between min-h-[36px]" style="min-height: 36px">
                    <h3 class="font-medium text-gray-400 dark:text-gray-500">
                        {{ $label }}
                    </h3>
                </div>

                <div class="flex flex-col gap-y-1">
                    @foreach ($views as $key => $view)
                        @php
                            $type = $view instanceof \Webkul\TableViews\Components\SavedView ? 'saved' : 'preset';
                        @endphp

                        <div class="flex items-center justify-between px-3 py-1 -mx-3 gap-x-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/5 hover:rounded-lg">
                            <div
                                class="flex w-full gap-x-2 truncate justify-between items-center"
                                wire:click="mountAction('applyTableView', JSON.parse('{\u0022view_key\u0022:\u0022{{$key}}\u0022, \u0022view_type\u0022:\u0022{{$type}}\u0022}'))"
                            >
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

                                            @if ($key == $activeTableView)
                                                <span class="text-primary-500">•</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <x-filament-actions::group
                                :actions="[
                                    ($this->applyTableViewAction)(['view_key' => $key, 'view_type' => $type])
                                        ->visible(fn () => $key != $activeTableView),
                                    ($this->addTableViewToFavoritesAction)(['view_key' => $key, 'view_type' => $type])
                                        ->visible(fn () => ! $view->isFavorite($key)),
                                    ($this->removeTableViewFromFavoritesAction)(['view_key' => $key, 'view_type' => $type])
                                        ->visible(fn () => $view->isFavorite($key)),
                                    ($this->editTableViewAction)(['view_model' => $view->getModel()])
                                        ->visible(fn () => $view->isEditable()),
                                    \Filament\Actions\ActionGroup::make([
                                        ($this->replaceTableViewAction)(['view_key' => $key, 'view_type' => $type])
                                            ->visible(fn () => $view->isReplaceable() && $key == $activeTableView && $isActiveTableViewModified),
                                        ($this->deleteTableViewAction)(['view_key' => $key, 'view_type' => $type])
                                            ->visible(fn () => $key == $view->isDeletable()),
                                    ])
                                        ->dropdown(false),
                                ]"
                                dropdown-placement="bottom-end"
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <x-filament-actions::modals />
    </div>
</div>
