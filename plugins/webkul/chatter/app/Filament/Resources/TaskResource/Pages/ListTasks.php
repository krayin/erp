<?php

namespace Webkul\Chatter\Filament\Resources\TaskResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Chatter\Filament\Resources\TaskResource;
use Webkul\TableViews\Components\PresetView;
use Webkul\TableViews\Filament\Traits\HasTableViews;

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
                ->modifyQueryUsing(fn (Builder $query) => $query->where('assigned_to', Auth::user()->id)),

            'pending_tasks' => PresetView::make('Pending Tasks')
                ->icon('heroicon-m-numbered-list')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
