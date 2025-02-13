<?php

namespace Webkul\Sale\Filament\Widgets;

use Illuminate\Contracts\Support\Htmlable;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Webkul\Sale\Models\Order;

class SaleTeamWidget extends ChartWidget
{
    public function getHeading(): string|Htmlable|null
    {
        return __('Sales Team Performance');
    }

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $dates = collect();
        $startDate = Carbon::now()->subWeeks(3)->startOfWeek();

        for ($i = 0; $i < 4; $i++) {
            $weekStart = $startDate->copy()->addWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();
            $dates->push([
                'start' => $weekStart,
                'end' => $weekEnd,
                'label' => $weekStart->format('d') . '-' . $weekEnd->format('d M'),
            ]);
        }

        $query = Order::query()
            ->selectRaw('
                sales_teams.name as team_name,
                sales_teams.invoiced_target,
                sales_teams.color,
                YEARWEEK(sales_orders.date_order) as year_week,
                SUM(sales_orders.amount_untaxed) as amount_untaxed
            ')
            ->rightJoin('sales_teams', 'sales_teams.id', '=', 'sales_orders.team_id')
            ->where('sales_teams.is_active', 1)
            ->whereBetween('sales_orders.date_order', [$dates->first()['start'], $dates->last()['end']])
            ->groupBy('sales_teams.name', 'sales_teams.invoiced_target', 'sales_teams.color', 'year_week')
            ->orderBy('year_week');

        $teamData = $query->get()->groupBy('team_name');

        $datasets = [];
        $labels = $dates->pluck('label')->toArray();

        foreach ($teamData as $teamName => $data) {
            $weeklyValues = [];
            $teamColor = $data->first()->color ?? '#7c7bad';

            foreach ($dates as $date) {
                $weekData = $data->first(function ($record) use ($date) {
                    return Carbon::parse($record->date_order)->between($date['start'], $date['end']);
                });

                $weeklyValues[] = $weekData ? round($weekData->amount_untaxed, 2) : 0;
            }

            $datasets[] = [
                'label' => $teamName,
                'data' => $weeklyValues,
                'backgroundColor' => $teamColor,
                'borderColor' => $teamColor,
                'borderWidth' => 1,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'elements' => [
                'line' => [
                    'fill' => true,
                ],
                'point' => [
                    'radius' => 4,
                    'hoverRadius' => 6,
                ],
            ],
        ];
    }
}
