@php
    use Webkul\SavedFilters\Enums\SavedFiltersLayout;
@endphp

@props([
    'actions',
    'activeSavedFiltersCount' => 0,
    'layout' => SavedFiltersLayout::Dropdown,
    'triggerAction',
])

@if (($layout === SavedFiltersLayout::Modal) || $triggerAction->isModalSlideOver())
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
            {{ $triggerAction->badge($activeSavedFiltersCount) }}
        </x-slot>

        {{ $triggerAction->getModalContent() }}

        {{ $triggerAction->getModalContentFooter() }}
    </x-filament::modal>
@else
    <x-filament::dropdown
        placement="bottom-end"
        shift
        wire:key="{{ $this->getId() }}.table.filters"
        {{ $attributes->class(['fi-ta-filters-dropdown']) }}
    >
        <x-slot name="trigger">
            {{ $triggerAction->badge($activeSavedFiltersCount) }}
        </x-slot>

        <x-saved-filters::tables.saved-filters
            :actions="$actions"
            class="p-6"
        />
    </x-filament::dropdown>
@endif
