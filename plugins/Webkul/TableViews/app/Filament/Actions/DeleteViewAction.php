<?php

namespace Webkul\TableViews\Filament\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Actions\Action;
use Webkul\TableViews\Models\TableView;

class DeleteViewAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'table_views.save.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->model(TableView::class)
            ->successNotificationTitle('View deleted successfully')
            ->icon('heroicon-o-plus')
            ->iconButton();
    }
}
