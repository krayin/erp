<?php

namespace Webkul\Project\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Webkul\Project\Models\Task;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return (string) Number::format($number, 0);
            }

            if ($number < 1000000) {
                return Number::format($number / 1000, 2).'k';
            }

            return Number::format($number / 1000000, 2).'m';
        };

        $formatHours = function (int $number): string {
            $hours = floor($number);
            $minutes = ($number - $hours) * 60;

            return $hours.':'.$minutes;
        };

        return [
            Stat::make('Total Tasks', $formatNumber(Task::count()))
                ->color('primary'),
            Stat::make('Hours Spent', $formatHours(Task::sum('effective_hours')))
                ->color('primary'),
            Stat::make('Time Remaining', $formatHours(Task::sum('remaining_hours')))
                ->color('primary'),
        ];
    }
}
