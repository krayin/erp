<?php

namespace Webkul\Chatter\Filament\Resources\TaskResource\Pages;

use Webkul\Chatter\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\SavedFilters\Filament\Traits\HasSavedFilters;
use Webkul\SavedFilters\Components\PresetFilter;
use Illuminate\Database\Eloquent\Builder;

class ListTasks extends ListRecords
{
    use HasSavedFilters;

    protected static string $resource = TaskResource::class;
    
    public function getPresetFilters(): array
    {
        return [
            'my_tasks' => PresetFilter::make('My Tasks')
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
