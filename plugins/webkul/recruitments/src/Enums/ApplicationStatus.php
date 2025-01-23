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
            self::ONGOING   => __('Ongoing'),
            self::HIRED     => __('Hired'),
            self::REFUSED   => __('Refused'),
            self::ARCHIVED  => __('Archived'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ONGOING   => '#17a2b8',
            self::HIRED     => '#28a745',
            self::REFUSED   => '#dc3545',
            self::ARCHIVED  => '#6c757d',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ONGOING   => 'heroicon-m-clock',
            self::HIRED     => 'heroicon-m-check-circle',
            self::REFUSED   => 'heroicon-m-x-circle',
            self::ARCHIVED  => 'heroicon-m-x-circle',
        };
    }

    public static function options(): array
    {
        return [
            self::ONGOING->value  => 'Ongoing',
            self::HIRED->value    => 'Hired',
            self::REFUSED->value  => 'Refused',
            self::ARCHIVED->value => 'Archived',
        ];
    }
}
