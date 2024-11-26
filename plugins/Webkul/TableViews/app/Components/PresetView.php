<?php

namespace Webkul\TableViews\Components;

use Closure;
use Filament\Resources\Components\Tab;

class PresetView extends Tab
{
    protected string | Closure | null $color = null;

    protected bool | Closure $isFavorite = false;

    protected bool | Closure $isDefault = false;

    public function color(string | Closure | null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getModel()
    {
        return null;
    }

    public function favorite(bool | Closure $condition = true): static
    {
        $this->isFavorite = $condition;

        return $this;
    }

    public function default(bool | Closure $condition = true): static
    {
        $this->isDefault = $condition;

        return $this;
    }

    public function isDefault(): bool
    {
        return (bool) $this->evaluate($this->isDefault);
    }

    public function isFavorite(): bool
    {
        return (bool) $this->evaluate($this->isFavorite);
    }

    /**
     * @return string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    public function getColor(): string | array | null
    {
        return $this->evaluate($this->color);
    }
}