<?php

namespace Webkul\Employee\Filament\Clusters\Reportings\Resources\EmployeeSkillResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Employee\Filament\Clusters\Reportings\Resources\EmployeeSkillResource;
use Webkul\TableViews\Components\PresetView;
use Webkul\TableViews\Filament\Traits\HasTableViews;

class ListEmployeeSkills extends ListRecords
{
    use HasTableViews;

    protected static string $resource = EmployeeSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getPresetTableViews(): array
    {
        return [
            'with_skills' => PresetView::make('With Skill')
                ->icon('heroicon-s-bolt')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('skill', fn ($q) => $q->whereNull('deleted_at'))),
            'without_skills' => PresetView::make('Without Skill')
                ->icon('heroicon-s-bolt-slash')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->doesntHave('skill')->whereNull('deleted_at')),
            'archived' => PresetView::make('Archived')
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }
}
