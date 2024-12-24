<?php

namespace Webkul\Project\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Webkul\Project\Enums\TaskState;
use Webkul\Project\Models\Task;

class TaskByStateChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Tasks By State';

    protected static ?string $maxHeight = '250px';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        foreach (TaskState::cases() as $state) {
            $query = Task::query();

            if (! empty($this->filters['selectedProjects'])) {
                $query->whereIn('project_id', $this->filters['selectedProjects']);
            }

            if (! empty($this->filters['selectedAssignees'])) {
                $query->whereHas('users', function ($q) {
                    $q->whereIn('users.id', $this->filters['selectedAssignees']);
                });
            }

            if (! empty($this->filters['selectedTags'])) {
                $query->whereHas('tags', function ($q) {
                    $q->whereIn('projects_task_tag.tag_id', $this->filters['selectedTags']);
                });
            }

            if (! empty($this->filters['selectedPartners'])) {
                $query->whereIn('parent_id', $this->filters['selectedPartners']);
            }

            $startDate = ! is_null($this->filters['startDate'] ?? null) ?
                Carbon::parse($this->filters['startDate']) :
                null;

            $endDate = ! is_null($this->filters['endDate'] ?? null) ?
                Carbon::parse($this->filters['endDate']) :
                now();

            $datasets['labels'][] = TaskState::options()[$state->value];

            $datasets['datasets'][] = $query
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('state', $state->value)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tasks created',
                    'data'  => $datasets['datasets'],
                ],
            ],
            'labels' => $datasets['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
