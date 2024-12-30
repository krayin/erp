<?php

namespace Webkul\Project\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Webkul\Project\Models\Timesheet;

class TopAssigneesWidget extends BaseWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    public function getHeading(): string|Htmlable|null
    {
        return __('projects::filament/widgets/top-assignees.heading');
    }

    protected static ?string $pollingInterval = '15s';

    protected function getTableQuery(): Builder
    {
        $query = Timesheet::query();

        if (! empty($this->filters['selectedProjects'])) {
            $query->whereIn('project_id', $this->filters['selectedProjects']);
        }

        if (! empty($this->filters['selectedAssignees'])) {
            $query->whereIn('user_id', $this->filters['selectedAssignees']);
        }

        if (! empty($this->filters['selectedPartners'])) {
            $query->whereIn('analytic_records.partner_id', $this->filters['selectedPartners']);
        }

        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        return $query
            ->join('users', 'users.id', '=', 'analytic_records.user_id')
            ->selectRaw('
                user_id,
                users.name as user_name,
                SUM(unit_amount) as total_hours,
                COUNT(DISTINCT task_id) as total_tasks
            ')
            ->whereBetween('analytic_records.created_at', [$startDate, $endDate])
            ->groupBy('user_id')
            ->orderByDesc('total_hours')
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user_name')
                ->label(__('projects::filament/widgets/top-assignees.table-columns.user'))
                ->sortable(),
            Tables\Columns\TextColumn::make('total_hours')
                ->label(__('projects::filament/widgets/top-assignees.table-columns.hours-spent'))
                ->sortable(),
            Tables\Columns\TextColumn::make('total_tasks')
                ->label(__('projects::filament/widgets/top-assignees.table-columns.tasks'))
                ->sortable(),
        ];
    }

    public function getTableRecordKey($record): string
    {
        return (string) $record->project_id;
    }
}
