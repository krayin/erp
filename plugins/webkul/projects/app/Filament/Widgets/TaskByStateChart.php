<?php

namespace Webkul\Project\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Webkul\Project\Enums\TaskState;
use Webkul\Project\Models\Task;

class TaskByStateChart extends ChartWidget
{
    protected static ?string $heading = 'Tasks By State';

    protected static ?string $maxHeight = '350px';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        foreach (TaskState::cases() as $state) {
            $datasets['labels'][] = TaskState::options()[$state->value];

            $datasets['datasets'][] = Task::where('state', $state->value)->count();
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
