<?php

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Enums\TaskState;
use Webkul\Project\Filament\Resources\TaskResource;
use Webkul\Project\Models\Task;
use Webkul\Project\Models\TaskStage;

class ManageSubTasks extends ManageRelatedRecords
{
    protected static string $resource = TaskResource::class;

    protected static string $relationship = 'subTasks';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationLabel(): string
    {
        return __('projects::filament/resources/task/pages/manage-sub-tasks.title');
    }

    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table)
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::Dropdown)
            ->filtersFormColumns(1)
            ->filtersTriggerAction(null)
            ->groups([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('projects::filament/resources/task/pages/manage-sub-tasks.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->fillForm(function (array $arguments): array {
                        return [
                            'stage_id'     => TaskStage::first()?->id,
                            'state'        => TaskState::IN_PROGRESS,
                            'project_id'   => $this->getOwnerRecord()->project_id,
                            'milestone_id' => $this->getOwnerRecord()->milestone_id,
                            'partner_id'   => $this->getOwnerRecord()->partner_id,
                            'users'        => $this->getOwnerRecord()->users->pluck('id')->toArray(),
                        ];
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->modalWidth('6xl')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('projects::filament/resources/task/pages/manage-sub-tasks.table.header-actions.create.notification.title'))
                            ->body(__('projects::filament/resources/task/pages/manage-sub-tasks.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->url(fn (Task $record): string => TaskResource::getUrl('view', ['record' => $record]))
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->url(fn (Task $record): string => TaskResource::getUrl('edit', ['record' => $record]))
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('projects::filament/resources/task/pages/manage-sub-tasks.table.actions.restore.notification.title'))
                                ->body(__('projects::filament/resources/task/pages/manage-sub-tasks.table.actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('projects::filament/resources/task/pages/manage-sub-tasks.table.actions.delete.notification.title'))
                                ->body(__('projects::filament/resources/task/pages/manage-sub-tasks.table.actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('projects::filament/resources/task/pages/manage-sub-tasks.table.actions.force-delete.notification.title'))
                                ->body(__('projects::filament/resources/task/pages/manage-sub-tasks.table.actions.force-delete.notification.body')),
                        ),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return TaskResource::infolist($infolist);
    }
}
