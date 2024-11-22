@if (method_exists($this, 'getTableViewsTriggerAction') && $tableViewsTriggerAction = $this->getTableViewsTriggerAction())
    @php
        $tableViewActions = $this->getTableViewActions();
        $activeTableViewsCount = $this->getActiveTableViewsCount();
        $tableViewsLayout = $this->getTableViewsLayout();
        $tableViewsFormMaxHeight = $this->getTableViewsFormMaxHeight();
        $tableViewsFormWidth = $this->getTableViewsFormWidth();

        $tableFavoriteViews = $this->getFavoriteTableViews();
        $tablePresetViews = $this->getPresetTableViews();
        $tableSavedViews = $this->getSavedTableViews();
    @endphp

    <x-table-views::tables.table-views.dialog
        :active-filters-count="$activeTableViewsCount"
        :layout="$tableViewsLayout"
        :trigger-action="$tableViewsTriggerAction"
        :actions="$tableViewActions"
        :favorite-views="$tableFavoriteViews"
        :preset-views="$tablePresetViews"
        :saved-views="$tableSavedViews"
        :max-height="$tableViewsFormMaxHeight"
        :width="$tableViewsFormWidth"
    />
@endif