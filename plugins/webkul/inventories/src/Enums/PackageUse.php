<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum PackageUse: string implements HasLabel
{
    case DISPOSABLE = 'disposable';

    public function getLabel(): string
    {
        return match ($this) {
            self::DISPOSABLE => __('inventories::enums/package-use.disposable'),
        };
    }
}
