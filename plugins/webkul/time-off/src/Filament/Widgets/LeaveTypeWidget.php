<?php

namespace Webkul\TimeOff\Filament\Widgets;

use Webkul\TimeOff\Models\Leave;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class LeaveTypeWidget extends ChartWidget
{
    public function getHeading(): string|Htmlable|null
    {
        return __('Time Off Analysis');
    }

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $query = Leave::query();

        if ($this->filters['selectedCompanies'] ?? null) {
            $query->whereIn('company_id', $this->filters['selectedCompanies']);
        }

        if ($this->filters['selectedDepartments'] ?? null) {
            $query->whereIn('department_id', $this->filters['selectedDepartments']);
        }

        if ($this->filters['startDate'] ?? null) {
            $query->where('request_date_from', '>=', Carbon::parse($this->filters['startDate'])->startOfDay());
        }

        if ($this->filters['endDate'] ?? null) {
            $query->where('request_date_to', '<=', Carbon::parse($this->filters['endDate'])->endOfDay());
        }

        $stats = $query->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN state = "draft" THEN 1 ELSE 0 END) as draft,
            SUM(CASE WHEN state = "confirm" THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN state = "validate" THEN 1 ELSE 0 END) as validated,
            SUM(CASE WHEN state = "refuse" THEN 1 ELSE 0 END) as refused,
            SUM(CASE WHEN state = "cancel" THEN 1 ELSE 0 END) as cancelled
        ')->first();

        $data = match ($this->filters['status'] ?? 'all') {
            'draft'     => ['Draft' => $stats->draft ?? 0],
            'confirmed' => ['Confirmed' => $stats->confirmed ?? 0],
            'validated' => ['Validated' => $stats->validated ?? 0],
            'refused'   => ['Refused' => $stats->refused ?? 0],
            'cancelled' => ['Cancelled' => $stats->cancelled ?? 0],
            default     => [
                'Draft'     => $stats->draft ?? 0,
                'Confirmed' => $stats->confirmed ?? 0,
                'Validated' => $stats->validated ?? 0,
                'Refused'   => $stats->refused ?? 0,
                'Cancelled' => $stats->cancelled ?? 0,
            ],
        };

        return [
            'datasets' => [
                [
                    'label'           => __('time_off::filament/widgets/leave.overview.label'),
                    'data'            => array_values($data),
                    'backgroundColor' => array_map(fn($key) => match ($key) {
                        'Draft'     => '#94a3b8',
                        'Confirmed' => '#3b82f6',
                        'Validated' => '#22c55e',
                        'Refused'   => '#ef4444',
                        'Cancelled' => '#f97316',
                    }, array_keys($data)),
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
