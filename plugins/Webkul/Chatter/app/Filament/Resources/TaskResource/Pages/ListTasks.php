<?php

namespace Webkul\Chatter\Filament\Resources\TaskResource\Pages;

use Webkul\Chatter\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\TableViews\Filament\Traits\HasTableViews;
use Webkul\TableViews\Components\PresetView;
use Illuminate\Database\Eloquent\Builder;

class ListTasks extends ListRecords
{
    use HasTableViews;

    protected static string $resource = TaskResource::class;
    
    public function getPresetTableViews(): array
    {
        return [
            'my_tasks' => PresetView::make('My Tasks')
                ->icon('heroicon-m-numbered-list')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->user()->id)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
