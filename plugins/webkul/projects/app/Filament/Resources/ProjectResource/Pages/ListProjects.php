<?php

namespace Webkul\Project\Filament\Resources\ProjectResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Resources\ProjectResource;
use Webkul\TableViews\Filament\Components\PresetView;
use Webkul\TableViews\Filament\Traits\HasTableViews;

class ListProjects extends ListRecords
{
    use HasTableViews;

    protected static string $resource = ProjectResource::class;

    public function getPresetTableViews(): array
    {
        return [
            'my_projects' => PresetView::make(__('projects::filament/resources/project/pages/list-project.tabs.my-projects'))
                ->icon('heroicon-s-user')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id())),

            'my_favorite_projects' => PresetView::make(__('projects::filament/resources/project/pages/list-project.tabs.my-favorite-projects'))
                ->icon('heroicon-s-star')
                ->favorite()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->leftJoin('projects_user_project_favorites', 'projects_user_project_favorites.project_id', '=', 'projects_projects.id')
                        ->where('projects_user_project_favorites.user_id', Auth::id());
                }),

            'unassigned_projects' => PresetView::make(__('projects::filament/resources/project/pages/list-project.tabs.unassigned-projects'))
                ->icon('heroicon-s-user-minus')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('user_id')),

            'archived_projects' => PresetView::make(__('projects::filament/resources/project/pages/list-project.tabs.archived-projects'))
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('projects::filament/resources/project/pages/list-project.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
