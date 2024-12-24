<?php

namespace Webkul\Project\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Webkul\Project\Models\Timesheet;

class TopProjectsWidget extends BaseWidget
{
    use InteractsWithPageFilters, HasWidgetShield;

    protected static ?string $pollingInterval = '15s';

    protected function getTableQuery(): Builder
    {
        $query = Timesheet::query();

        if (! empty($this->filters['selectedProjects'])) {
            $query->whereIn('project_id', $this->filters['selectedProjects']);
        }

        if (! empty($this->filters['selectedAssignees'])) {
            $query->whereIn('analytic_records.user_id', $this->filters['selectedAssignees']);
        }

        if (! empty($this->filters['selectedTags'])) {
            $query->whereHas('project.tags', function ($q) {
                $q->whereIn('projects_project_tag.tag_id', $this->filters['selectedTags']);
            });
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
            ->join('projects_projects', 'projects_projects.id', '=', 'analytic_records.project_id')
            ->selectRaw('
                analytic_records.project_id, 
                projects_projects.name as project_name, 
                SUM(analytic_records.unit_amount) as total_hours, 
                COUNT(DISTINCT analytic_records.task_id) as total_tasks
            ')
            ->whereBetween('analytic_records.created_at', [$startDate, $endDate])
            ->groupBy('analytic_records.project_id', 'projects_projects.name')
            ->orderByDesc('total_hours')
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('project_name')
                ->label('Project Name')
                ->sortable(),
            Tables\Columns\TextColumn::make('total_hours')
                ->label('Hours Spent')
                ->sortable(),
            Tables\Columns\TextColumn::make('total_tasks')
                ->label('Tasks')
                ->sortable(),
        ];
    }

    public function getTableRecordKey($record): string
    {
        return (string) $record->project_id;
    }
}
