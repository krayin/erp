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
        return 'Sub Tasks';
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
                    ->label('Add Sub Task')
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
                            ->title('Timesheet created')
                            ->body('The timesheet has been created successfully.'),
                    ),
            ])
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
}
