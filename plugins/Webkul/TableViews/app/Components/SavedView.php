<?php

namespace Webkul\TableViews\Components;

use Webkul\TableViews\Models\TableView;

class SavedView extends PresetView
{
    protected TableView $model;

    public function model(TableView $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): TableView
    {
        return $this->model;
    }
}