<?php

namespace Webkul\Project\Models;

use Webkul\Analytic\Models\Record;

class Timesheet extends Record
{
    /**
     * Bootstrap any application services.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($timesheet) {
            $timesheet->updateTaskTimes();
        });

        static::updated(function ($timesheet) {
            $timesheet->updateTaskTimes();
        });

        static::deleted(function ($timesheet) {
            $timesheet->updateTaskTimes();
        });
    }

    public function updateTaskTimes()
    {
        if (! $this->task) {
            return;
        }

        $totalTime = $this->task->timesheets()->sum('unit_amount');

        $this->task->update([
            'total_hours_spent' => $totalTime,
            'effective_hours'   => $totalTime,
            'overtime'          => $totalTime > $this->task->allocated_hours ? $totalTime - $this->task->allocated_hours : 0,
            'remaining_hours'   => $this->task->allocated_hours - $totalTime,
            'progress'          => $this->task->allocated_hours ? ($totalTime / $this->task->allocated_hours) * 100 : 0,
        ]);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
