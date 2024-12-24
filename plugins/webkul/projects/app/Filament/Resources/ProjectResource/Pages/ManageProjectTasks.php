<?php

namespace Webkul\Project\Filament\Resources\ProjectResource\Pages;

use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Webkul\Project\Filament\Resources\ProjectResource;
use Webkul\Project\Filament\Resources\TaskResource;
use Webkul\Project\Models\Task;
use Webkul\TableViews\Filament\Traits\HasTableViews;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Project\Enums\TaskState;
use Illuminate\Support\Facades\Auth;
use Webkul\TableViews\Components\PresetView;

class ManageProjectTasks extends ManageRelatedRecords
{
    use HasTableViews;

    protected static string $resource = ProjectResource::class;

    protected static string $relationship = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Tasks';
    }

    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Task')
                    ->url(route('filament.admin.resources.project.tasks.create')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->url(fn (Task $record): string => route('filament.admin.resources.project.tasks.view', $record->id))
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->url(fn (Task $record): string => route('filament.admin.resources.project.tasks.edit', $record->id))
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return TaskResource::infolist($infolist);
    }
    
    public function getPresetTableViews(): array
    {
        return [
            'open_tasks' => PresetView::make('Open Tasks')
                ->icon('heroicon-s-bolt')
                ->favorite()
                ->default()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotIn('state', [
                    TaskState::CANCELLED,
                    TaskState::DONE,
                ])),

            'my_tasks' => PresetView::make('My Tasks')
                ->icon('heroicon-s-user')
                ->favorite()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->whereHas('users', function ($q) {
                            $q->where('user_id', Auth::id());
                        });
                }),

            'unassigned_tasks' => PresetView::make('Unassigned Tasks')
                ->icon('heroicon-s-user-minus')
                ->favorite()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->whereDoesntHave('users');
                }),

            'closed_tasks' => PresetView::make('Closed Tasks')
                ->icon('heroicon-s-check-circle')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('state', [
                    TaskState::CANCELLED,
                    TaskState::DONE,
                ])),

            'starred_tasks' => PresetView::make('Starred Tasks')
                ->icon('heroicon-s-star')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('priority', true)),

            'archived_tasks' => PresetView::make('Archived Tasks')
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
