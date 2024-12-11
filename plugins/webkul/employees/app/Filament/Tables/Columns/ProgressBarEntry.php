<?php

namespace Webkul\Employee\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;

class ProgressBarEntry extends Column
{
    protected $canShow = true;

    protected string $view = 'employee::tables.columns.progress-bar-entry';

    public function hideProgressValue($canShow = false)
    {
        $this->canShow = $canShow;

        return $this;
    }

    public function getCanShow(): bool
    {
        return $this->canShow;
    }
}
