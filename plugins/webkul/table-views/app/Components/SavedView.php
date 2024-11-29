<?php

namespace Webkul\TableViews\Components;

use Webkul\TableViews\Models\TableView;
use Webkul\TableViews\Models\TableViewFavorite;

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

    public function isFavorite(string | int $id = null): bool
    {
        $tableViewFavorite = TableViewFavorite::query()
            ->where('user_id', auth()->id())
            ->where('view_type', 'saved')
            ->where('view_key', $id ?? $this->model->id)
            ->first();

        return (bool) ($tableViewFavorite?->is_favorite ?? $this->isFavorite);
    }

    public function isEditable(): bool
    {
        return $this->model->user_id === auth()->id();
    }

    public function isReplaceable(): bool
    {
        return $this->model->user_id === auth()->id();
    }

    public function isDeletable(): bool
    {
        return $this->model->user_id === auth()->id();
    }
}