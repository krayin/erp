<?php

namespace Webkul\Support\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns\HasColor;

class ProgressBarEntry extends Column
{
    use HasColor {
        getColor as getBaseColor;
    }

    protected $canShow = true;

    protected string $view = 'support::tables.columns.progress-bar-entry';

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
