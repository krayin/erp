@if (method_exists($this, 'getCachedFavoriteTableViews') && count($tabs = $this->getCachedFavoriteTableViews()))
    @php
        $activeTableView = strval($this->activeTableView);
        $isActiveTableViewModified = $this->isActiveTableViewModified();
    @endphp

    <div
        class="flex gap-4 justify-between items-center p-2 items-center"
        style="margin-bottom: -40px"
        wire:listen="filtered-list-updated"
    >
        <nav class="fi-tabs flex max-w-full gap-x-1 overflow-x-auto p-2">
            @foreach ($tabs as $tabKey => $tab)
                @php
                    $tabKey = strval($tabKey);

                    $color = $tab->getColor() ?: 'primary';
                @endphp

                <x-filament::tabs.item
                    class="whitespace-nowrap"
                    :active="$activeTableView === $tabKey"
                    :badge="$tab->getBadge()"
                    :badge-color="$tab->getBadgeColor()"
                    :badge-icon="$tab->getBadgeIcon()"
                    :badge-icon-position="$tab->getBadgeIconPosition()"
                    :icon="$tab->getIcon()"
                    :icon-position="$tab->getIconPosition()"
                    :wire:click="'$call(\'loadView\', ' . (filled($tabKey) ? ('\'' . $tabKey . '\'') : 'null') . ')'"
                    :attributes="$tab->getExtraAttributeBag()"
                    @style([
                        'border-bottom: 2px solid transparent; border-radius: 0',
                        'border-bottom: 2px solid rgb(var(--'.$color.'-500))' => $activeTableView === $tabKey,
                        'border-bottom: 2px solid rgb(var(--gray-300))' => $activeTableView === $tabKey && $isActiveTableViewModified,
                    ])
                >
                    {{ $tab->getLabel() ?? $this->generateTabLabel($tabKey) }}
                </x-filament::tabs.item>
            @endforeach
        </nav>

        <div class="flex gap-2">
            <x-filament-actions::modals />

            <x-filament::loading-indicator
                :attributes="
                    \Filament\Support\prepare_inherited_attributes(
                        new \Illuminate\View\ComponentAttributeBag([
                            'wire:loading.delay.' . config('filament.livewire_loading_delay', 'default') => '',
                            'wire:loading' => 'wire:loading',
                            'wire:target' => 'loadView',
                        ])
                    )->class(['h-5 w-5 text-gray-400 dark:text-gray-500'])
                "
            />

            {{ $this->saveFilterAction }}
        </div>
    </div>
@endif