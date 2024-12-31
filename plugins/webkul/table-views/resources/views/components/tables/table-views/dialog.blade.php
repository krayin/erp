@php
    use Webkul\TableViews\Enums\TableViewsLayout;
@endphp

@props([
    'activeTableView' => null,
    'activeTableViewsCount' => 0,
    'isActiveTableViewModified' => false,
    'layout' => TableViewsLayout::Dropdown,
    'maxHeight' => null,
    'width' => 'xs',
    'triggerAction',
    'favoriteViews' => [],
    'savedViews' => [],
    'presetViews' => [],
])

@if (($layout === TableViewsLayout::Modal) || $triggerAction->isModalSlideOver())
    <x-filament::modal
        :alignment="$triggerAction->getModalAlignment()"
        :autofocus="$triggerAction->isModalAutofocused()"
        :close-button="$triggerAction->hasModalCloseButton()"
        :close-by-clicking-away="$triggerAction->isModalClosedByClickingAway()"
        :close-by-escaping="$triggerAction?->isModalClosedByEscaping()"
        :description="$triggerAction->getModalDescription()"
        :footer-actions="$triggerAction->getVisibleModalFooterActions()"
        :footer-actions-alignment="$triggerAction->getModalFooterActionsAlignment()"
        :heading="$triggerAction->getCustomModalHeading() ?? __('filament-tables::table.filters.heading')"
        :icon="$triggerAction->getModalIcon()"
        :icon-color="$triggerAction->getModalIconColor()"
        :slide-over="$triggerAction->isModalSlideOver()"
        :sticky-footer="$triggerAction->isModalFooterSticky()"
        :sticky-header="$triggerAction->isModalHeaderSticky()"
        wire:key="{{ $this->getId() }}.table.filters"
        {{ $attributes->class(['fi-ta-filters-modal']) }}
    >
        <x-slot name="trigger">
            {{ $triggerAction->badge($activeTableViewsCount ?: null) }}
        </x-slot>

        <x-table-views::tables.table-views
            :active-table-view="$activeTableView"
            :is-active-table-view-modified="$isActiveTableViewModified"
            :favorite-views="$favoriteViews"
            :preset-views="$presetViews"
            :saved-views="$savedViews"
            class="p-6"
        />

        {{ $triggerAction->getModalContent() }}

        {{ $triggerAction->getModalContentFooter() }}
    </x-filament::modal>
@else
    <x-filament::dropdown
        :max-height="$maxHeight"
        placement="bottom-end"
        shift
        :width="$width"
        wire:key="{{ $this->getId() }}.table.filters"
        {{ $attributes->class(['fi-ta-filters-dropdown']) }}
    >
        <x-slot name="trigger">
            {{ $triggerAction->badge($activeTableViewsCount ?: null) }}
        </x-slot>

        <x-table-views::tables.table-views
            :active-table-view="$activeTableView"
            :is-active-table-view-modified="$isActiveTableViewModified"
            :favorite-views="$favoriteViews"
            :preset-views="$presetViews"
            :saved-views="$savedViews"
            class="p-6"
        />
    </x-filament::dropdown>
@endif
