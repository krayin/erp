<?php

namespace Webkul\Employee\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum WorkLocation: string implements HasColor, HasIcon, HasLabel
{
    case Home = 'home';

    case Office = 'office';

    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Home   => __('employees::enums/work-location.home'),
            self::Office => __('employees::enums/work-location.office'),
            self::Other  => __('employees::enums/work-location.other'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Home   => 'success',
            self::Office => 'warning',
            self::Other  => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Home   => 'heroicon-m-home',
            self::Office => 'heroicon-m-building-office-2',
            self::Other  => 'heroicon-m-map-pin',
        };
    }
}
