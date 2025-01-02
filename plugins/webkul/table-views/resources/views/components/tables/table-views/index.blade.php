@props([
    'activeTableView',
    'isActiveTableViewModified',
    'favoriteViews' => [],
    'savedViews' => [],
    'presetViews' => [],
])

<div {{ $attributes->class(['fi-ta-filters grid gap-y-4']) }}>
    <div class="flex items-center justify-between">
        <h4 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
            @lang('table-views::app.views.component.tables.table-views.title')
        </h4>

        <div>
            <x-filament::link
                :attributes="
                    \Filament\Support\prepare_inherited_attributes(
                        new \Illuminate\View\ComponentAttributeBag([
                            'color' => 'danger',
                            'tag' => 'button',
                            'wire:click' => 'resetTableViews',
                            'wire:loading.remove.delay.'.config('filament.livewire_loading_delay', 'default') => '',
                            'wire:target' => 'resetTableViews',
                        ])
                    )
                "
            >
                @lang('table-views::app.views.component.tables.table-views.reset')
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
            __('table-views::app.views.component.tables.table-views.favorites-views') => $favoriteViews,
            __('table-views::app.views.component.tables.table-views.saved-views') => array_diff_key($savedViews, $favoriteViews),
            __('table-views::app.views.component.tables.table-views.preset-views') => array_diff_key($presetViews, $favoriteViews),
        ] as $label => $views)
            @if (! empty($views))
                <div
                    class="flex flex-col"
                    x-data="{ reorderViews: false }"
                >
                    <div class="flex min-h-[36px] items-center justify-between" style="min-height: 36px">
                        <h3 class="font-medium text-gray-400 dark:text-gray-500">
                            {{ $label }}
                        </h3>

                        {{-- <x-filament::icon-button
                            icon="heroicon-o-arrows-up-down"
                            color="gray"
                            size="sm"
                            x-on:click="reorderViews = true"
                            x-show="reorderViews === false"
                        />

                        <x-filament::icon-button
                            icon="heroicon-c-check"
                            color="gray"
                            size="sm"
                            x-on:click="reorderViews = false"
                            x-show="reorderViews === true"
                        /> --}}
                    </div>

                    <div
                        class="flex flex-col gap-y-1"
                        x-data="{}"
                        x-sortable
                        x-on:start="reorderViews === true"
                        x-on:end="console.log('Sorting ended!', $event.detail)"
                    >
                        @foreach ($views as $key => $view)
                            @php
                                $type = $view instanceof \Webkul\TableViews\Filament\Components\SavedView ? 'saved' : 'preset';
                            @endphp

                            <div
                                class="-mx-3 flex cursor-pointer items-center justify-between gap-x-3 px-3 py-1 hover:rounded-lg hover:bg-gray-100 dark:hover:bg-white/5"
                                style="margin-left: -.75rem; margin-right: -.75rem;"
                                x-bind:class="{
                                    'cursor-move': reorderViews === true
                                }"
                                x-sortable-handle
                                x-bind:x-sortable-item="reorderViews === true && '{{ $key }}'"
                            >
                                <div
                                    class="flex w-full items-center justify-between gap-x-2 truncate"
                                    x-bind:class="{
                                        'cursor-move': reorderViews === true
                                    }"
                                    x-on:click="
                                        reorderViews === false
                                            ? $wire.call('mountAction', 'applyTableView', JSON.parse('{\u0022view_key\u0022:\u0022{{$key}}\u0022, \u0022view_type\u0022:\u0022{{$type}}\u0022}'))
                                            : null
                                    "
                                >
                                    <div class="flex h-9 flex-1 items-center truncate">
                                        <div class="flex w-full items-center gap-x-3 truncate">
                                            <x-filament::icon
                                                :icon="$view->getIcon()"
                                                class="h-5 w-5 text-gray-400 dark:text-gray-400"
                                            />

                                            <div class="flex items-center gap-x-2 truncate" style="">
                                                <span class="select-none truncate">
                                                    {{ $view->getLabel() }}
                                                </span>

                                                @if ($key == $activeTableView)
                                                    <span class="text-primary-500">•</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <x-filament::icon
                                    :icon="
                                        $type == 'saved'
                                            ? $view->isPublic() ? 'heroicon-o-eye' : 'heroicon-o-user'
                                            : 'heroicon-o-lock-closed'
                                    "
                                    class="h-6 w-6 text-gray-400 dark:text-gray-200"
                                />

                                <div x-show="reorderViews === false">
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

                                <div x-show="reorderViews === true">
                                    <x-filament::icon-button
                                        icon="heroicon-m-equals"
                                        size="sm"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <x-filament-actions::modals />
    </div>
</div>
