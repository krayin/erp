<?php

namespace Webkul\Employee\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum WorkLocationEnum: string implements HasColor, HasIcon, HasLabel
{
    case Home = 'home';

    case Office = 'office';

    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Home   => 'Home',
            self::Office => 'Office',
            self::Other  => 'Other',
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
