<?php

namespace Webkul\Project\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Project\Enums\TaskState;
use Webkul\Project\Filament\Resources\TaskResource\Pages;
use Webkul\Project\Filament\Resources\TaskResource\RelationManagers;
use Webkul\Project\Models\Task;
use Webkul\Project\Models\TaskStage;
use Webkul\Project\Filament\Resources\ProjectResource\Pages\ManageProjectTasks;

class TaskResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Task::class;

    protected static ?string $slug = 'project/tasks';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Project';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\ToggleButtons::make('stage_id')
                            ->hiddenLabel()
                            ->inline()
                            ->required()
                            ->options(fn () => TaskStage::all()->mapWithKeys(fn ($stage) => [$stage->id => $stage->name]))
                            ->default(TaskStage::first()?->id),
                        Forms\Components\Section::make('General Information')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Task Title...')
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                                Forms\Components\ToggleButtons::make('state')
                                    ->required()
                                    ->default('in_progress')
                                    ->inline()
                                    ->options([
                                        'in_progress'      => 'In Progress',
                                        'change_requested' => 'Change Requested',
                                        'approved'         => 'Approved',
                                        'cancelled'        => 'Cancelled',
                                        'done'             => 'Done',
                                    ])
                                    ->colors([
                                        'in_progress'      => 'gray',
                                        'change_requested' => 'warning',
                                        'approved'         => 'success',
                                        'cancelled'        => 'danger',
                                        'done'             => 'success',
                                    ])
                                    ->icons([
                                        'in_progress'      => 'heroicon-m-play-circle',
                                        'change_requested' => 'heroicon-s-exclamation-circle',
                                        'approved'         => 'heroicon-o-check-circle',
                                        'cancelled'        => 'heroicon-s-x-circle',
                                        'done'             => 'heroicon-c-check-circle',
                                    ]),
                                Forms\Components\Select::make('tags')
                                    ->label('Tags')
                                    ->relationship(name: 'tags', titleAttribute: 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->unique('projects_tags'),
                                        Forms\Components\ColorPicker::make('color'),
                                    ]),
                                Forms\Components\RichEditor::make('description')
                                    ->label('Description'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Settings')
                            ->schema([
                                Forms\Components\Select::make('project_id')
                                    ->label('Project')
                                    ->relationship('project', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('milestone_id')
                                    ->label('Milestone')
                                    ->relationship('milestone', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('partner_id')
                                    ->label('Customer')
                                    ->relationship('partner', 'name')
                                    ->searchable(),
                                Forms\Components\Select::make('partner_id')
                                    ->label('Milestone')
                                    ->relationship('partner', 'name')
                                    ->searchable()
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Deliver your services automatically when a milestone is reached by linking it to a sales order item.'),
                                Forms\Components\Select::make('user_id')
                                    ->label('Assignees')
                                    ->relationship('users', 'name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                                Forms\Components\DateTimePicker::make('deadline')
                                    ->label('Deadline')
                                    ->native(false),
                                Forms\Components\TextInput::make('allocated_hours')
                                    ->label('Allocated Hours')
                                    ->numeric(),
                            ]),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('priority')
                    ->onIcon('heroicon-s-star')
                    ->onColor('warning')
                    ->offIcon('heroicon-o-star')
                    ->offColor('gray'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->hiddenOn(ManageProjectTasks::class)
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Private Task'),
                Tables\Columns\TextColumn::make('milestone.name')
                    ->label('Milestone')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Assignees')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('allocated_hours')
                    ->label('Allocated Time')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Sum::make()->numeric()),
                Tables\Columns\TextColumn::make('total_hours_spent')
                    ->label('Time Spent')
                    ->sortable()
                    ->toggleable()
                    ->numeric()
                    ->summarize(Sum::make()->numeric()),
                Tables\Columns\TextColumn::make('remaining_hours')
                    ->label('Time Remaining')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Sum::make()->numeric()),
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Deadline')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('stage.name')
                    ->label('Stage')
                    ->sortable()
                    ->toggleable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('project.name')
                    ->label('Project'),
                Tables\Grouping\Group::make('deadline')
                    ->label('Deadline')
                    ->date(),
                Tables\Grouping\Group::make('stage.name')
                    ->label('Stage'),
                Tables\Grouping\Group::make('milestone.name')
                    ->label('Milestone'),
                Tables\Grouping\Group::make('partner.name')
                    ->label('Customer'),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('users')
                            ->label('Assignees')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('partner')
                            ->label('Customer')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('project')
                            ->label('Project')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('stage')
                            ->label('Stage')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\SelectConstraint::make('state')
                            ->label('State')
                            ->multiple()
                            ->options([
                                TaskState::IN_PROGRESS->value      => 'In Progress',
                                TaskState::CHANGE_REQUESTED->value => 'Change Requested',
                                TaskState::APPROVED->value         => 'Approved',
                                TaskState::CANCELLED->value        => 'Cancelled',
                                TaskState::DONE->value             => 'Done',
                            ]),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('tags')
                            ->label('Tags')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('allocated_hours')
                            ->label('Allocated Hours'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('total_hours_spent')
                            ->label('Total Hours Spent'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('remaining_hours')
                            ->label('Remaining Hours'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('deadline'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                    ]),
            ])
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Timesheets', [
                RelationManagers\SubTasksRelationManager::class,
            ]),

            RelationGroup::make('Sub Tasks', [
                RelationManagers\SubTasksRelationManager::class,
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit'   => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
