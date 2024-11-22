@php
    $savedFiltersTriggerAction = $this->getSavedFiltersTriggerAction();
    $savedFilterActions = $this->getSavedFilterActions();
    $activeSavedFiltersCount = $this->getActiveSavedFiltersCount();
    $savedFiltersLayout = $this->getSavedFiltersLayout();
    $savedFiltersFormMaxHeight = $this->getSavedFiltersFormMaxHeight();
    $savedFiltersFormWidth = $this->getSavedFiltersFormWidth();
@endphp

<x-saved-filters::tables.saved-filters.dialog
    :active-filters-count="$activeSavedFiltersCount"
    :layout="$savedFiltersLayout"
    :trigger-action="$savedFiltersTriggerAction"
    :actions="$savedFilterActions"
    :max-height="$savedFiltersFormMaxHeight"
    :width="$savedFiltersFormWidth"
/>