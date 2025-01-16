<?php

namespace Webkul\Recruitment\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RecruitmentState: string implements HasColor, HasIcon, HasLabel
{
    case NORMAL = 'normal';
    case DONE = 'done';
    case BLOCKED = 'blocked';

    public function getLabel(): string
    {
        return match ($this) {
            self::NORMAL  => __('In Progress'),
            self::DONE    => __('Ready for next stage'),
            self::BLOCKED => __('Blocked'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NORMAL  => 'gray',
            self::DONE    => 'success',
            self::BLOCKED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::NORMAL  => 'heroicon-s-exclamation-circle',
            self::DONE    => 'heroicon-s-check-badge',
            self::BLOCKED => 'heroicon-s-shield-exclamation',
        };
    }

    public static function options(): array
    {
        return [
            self::NORMAL->value  => __('Normal'),
            self::DONE->value    => __('Done'),
            self::BLOCKED->value => __('Blocked'),
        ];
    }
}
