<?php

namespace Webkul\Project\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Webkul\Project\Models\Task;
use Webkul\Project\Models\TaskStage;

class TaskByStageChart extends ChartWidget
{
    protected static ?string $heading = 'Tasks By Stage';

    protected static ?string $maxHeight = '350px';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $datasets = [
            'datasets' => [],
            'labels'   => [],
        ];

        foreach (TaskStage::all() as $stage) {
            if (in_array($stage->name, $datasets['labels'])) {
                $datasets['labels'][] = $stage->name.' '.$stage->id;
            } else {
                $datasets['labels'][] = $stage->name;
            }

            $datasets['datasets'][] = Task::where('stage_id', $stage->id)->count();
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
        return 'bar';
    }
}
