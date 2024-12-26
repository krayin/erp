<?php

namespace Webkul\Project\Filament\Resources\ProjectResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Enums\TaskState;
use Webkul\Project\Filament\Resources\ProjectResource;
use Webkul\Project\Filament\Resources\TaskResource;
use Webkul\Project\Models\Task;
use Webkul\TableViews\Components\PresetView;
use Webkul\TableViews\Filament\Traits\HasTableViews;
use Filament\Notifications\Notification;

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

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Task')
                ->icon('heroicon-o-plus-circle')
                ->url(route('filament.admin.resources.project.tasks.create')),
        ];
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->url(fn (Task $record): string => route('filament.admin.resources.project.tasks.view', $record->id))
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->url(fn (Task $record): string => route('filament.admin.resources.project.tasks.edit', $record->id))
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Task restored')
                                ->body('The task has been restored successfully.'),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Task deleted')
                                ->body('The task has been deleted successfully.'),
                        ),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Task force deleted')
                                ->body('The task has been force deleted successfully.'),
                        ),
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
