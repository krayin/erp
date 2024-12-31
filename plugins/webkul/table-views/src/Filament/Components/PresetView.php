<?php

namespace Webkul\TableViews\Filament\Components;

use Closure;
use Filament\Resources\Components\Tab;
use Webkul\TableViews\Models\TableViewFavorite;

class PresetView extends Tab
{
    protected string|Closure|null $id = null;

    protected string|Closure|null $color = null;

    protected bool|Closure $isDefault = false;

    protected bool|Closure $isFavorite = false;

    protected bool|Closure $isEditable = false;

    protected bool|Closure $isReplaceable = false;

    protected bool|Closure $isDeletable = false;

    public function color(string|Closure|null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getModel()
    {
        return null;
    }

    public function favorite(bool|Closure $condition = true): static
    {
        $this->isFavorite = $condition;

        return $this;
    }

    public function default(bool|Closure $condition = true): static
    {
        $this->isDefault = $condition;

        return $this;
    }

    public function isDefault(): bool
    {
        return (bool) $this->evaluate($this->isDefault);
    }

    public function isFavorite(string|int|null $id = null): bool
    {
        $tableViewFavorite = TableViewFavorite::query()
            ->where('user_id', auth()->id())
            ->where('view_type', 'preset')
            ->where('view_key', $id)
            ->first();

        return (bool) ($tableViewFavorite?->is_favorite ?? $this->evaluate($this->isFavorite));
    }

    public function isEditable(): bool
    {
        return $this->isEditable;
    }

    public function isReplaceable(): bool
    {
        return $this->isReplaceable;
    }

    public function isDeletable(): bool
    {
        return $this->isDeletable;
    }

    /**
     * @return string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    public function getColor(): string|array|null
    {
        return $this->evaluate($this->color);
    }
}
