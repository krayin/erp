@if (method_exists($this, 'getTableViewsTriggerAction') && $tableViewsTriggerAction = $this->getTableViewsTriggerAction())
    @php
        $activeTableView = $this->getActiveTableView();
        $activeTableViewsCount = $this->getActiveTableViewsCount();
        $isActiveTableViewModified = $this->isActiveTableViewModified();
        $tableViewsLayout = $this->getTableViewsLayout();
        $tableViewsFormMaxHeight = $this->getTableViewsFormMaxHeight();
        $tableViewsFormWidth = $this->getTableViewsFormWidth();

        $tableFavoriteViews = $this->getFavoriteTableViews();
        $tablePresetViews = $this->getPresetTableViews();
        $tableSavedViews = $this->getSavedTableViews();
    @endphp

    <x-table-views::tables.table-views.dialog
        :active-table-view="$activeTableView"
        :active-table-views-count="$activeTableViewsCount"
        :is-active-table-view-modified="$isActiveTableViewModified"
        :layout="$tableViewsLayout"
        :trigger-action="$tableViewsTriggerAction"
        :favorite-views="$tableFavoriteViews"
        :preset-views="$tablePresetViews"
        :saved-views="$tableSavedViews"
        :max-height="$tableViewsFormMaxHeight"
        :width="$tableViewsFormWidth"
    />
@endif