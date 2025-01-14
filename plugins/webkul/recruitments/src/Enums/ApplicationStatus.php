<?php

namespace Webkul\Recruitment\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ApplicationStatus: string implements HasColor, HasIcon, HasLabel
{
    case ONGOING = 'ongoing';
    case HIRED = 'hired';
    case REFUSED = 'refused';
    case ARCHIVED = 'archived';


    public function getLabel(): string
    {
        return match ($this) {
            self::Home   => __('employees::enums/work-location.home'),
            self::Office => __('employees::enums/work-location.office'),
            self::Other  => __('employees::enums/work-location.other'),
        };
    }

    public static function options(): array
    {
        return [
            self::ONGOING->value => 'Ongoing',
            self::HIRED->value => 'Hired',
            self::REFUSED->value => 'Refused',
            self::ARCHIVED->value => 'Archived',
        ];
    }
}
