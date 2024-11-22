@if (method_exists($this, 'getCachedFavoriteSavedFilters') && count($tabs = $this->getCachedFavoriteSavedFilters()))
    @php
        $activeSavedFilter = strval($this->activeSavedFilter);
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
                    :active="$activeSavedFilter === $tabKey"
                    :badge="$tab->getBadge()"
                    :badge-color="$tab->getBadgeColor()"
                    :badge-icon="$tab->getBadgeIcon()"
                    :badge-icon-position="$tab->getBadgeIconPosition()"
                    :icon="$tab->getIcon()"
                    :icon-position="$tab->getIconPosition()"
                    :wire:click="'$call(\'loadFilter\', ' . (filled($tabKey) ? ('\'' . $tabKey . '\'') : 'null') . ')'"
                    :attributes="$tab->getExtraAttributeBag()"
                    class="fi-color-{{ $color }}"
                    @style([
                        'border-bottom: 2px solid transparent; border-radius: 0',
                        'border-bottom: 2px solid rgb(var(--'.$color.'-500))' => $activeSavedFilter === $tabKey,
                    ])
                >
                    {{ $tab->getLabel() ?? $this->generateTabLabel($tabKey) }}
                </x-filament::tabs.item>
            @endforeach
        </nav>

        <div class="flex gap-2">
            <x-filament-actions::modals />

            {{ $this->saveFilterAction }}
        </div>
    </div>
@endif