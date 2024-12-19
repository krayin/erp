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
use Illuminate\Support\Facades\Auth;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Project\Enums\TaskState;
use Webkul\Project\Filament\Resources\ProjectResource\Pages\ManageProjectTasks;
use Webkul\Project\Filament\Resources\TaskResource\Pages;
use Webkul\Project\Filament\Resources\TaskResource\RelationManagers;
use Webkul\Project\Models\Project;
use Webkul\Project\Models\Task;
use Webkul\Project\Models\TaskStage;
use Webkul\Security\Filament\Resources\UserResource;
use Webkul\Project\Settings\TaskSettings;
use Webkul\Project\Settings\TimeSettings;
use Webkul\Employee\Filament\Tables\Columns\ProgressBarEntry;

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
                                    ->options(TaskState::options())
                                    ->colors(TaskState::colors())
                                    ->icons(TaskState::icons()),
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
                                    ->preload()
                                    ->live()
                                    ->createOptionForm(fn (Form $form): Form => ProjectResource::form($form))
                                    ->afterStateUpdated(function (Forms\Set $set) {
                                        $set('milestone_id', null);
                                    }),
                                Forms\Components\Select::make('milestone_id')
                                    ->label('Milestone')
                                    ->relationship(
                                        name: 'milestone',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Forms\Get $get, \Illuminate\Database\Eloquent\Builder $query) => $query->where('project_id', $get('project_id')),
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Deliver your services automatically when a milestone is reached by linking it to a sales order item.')
                                    ->createOptionForm(fn ($get) => [
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\DateTimePicker::make('deadline')
                                            ->native(false)
                                            ->suffixIcon('heroicon-o-clock'),
                                        Forms\Components\Toggle::make('is_completed')
                                            ->required(),
                                        Forms\Components\Hidden::make('project_id')
                                            ->default($get('project_id')),
                                        Forms\Components\Hidden::make('creator_id')
                                            ->default(fn () => Auth::user()->id),
                                    ])
                                    // ->hidden(fn (Forms\Get $get) => ! $get('project_id'))
                                    ->hidden(function (TaskSettings $taskSettings, Forms\Get $get) {
                                        $project = Project::find($get('project_id'));

                                        if (! $project) {
                                            return true;
                                        }

                                        if (! $taskSettings->enable_milestones) {
                                            return true;
                                        }

                                        return ! $project->allow_milestones;
                                    })
                                    ->visible(fn (TaskSettings $taskSettings) => $taskSettings->enable_milestones),
                                    // ->visible(function (TaskSettings $taskSettings, Forms\Get $get) {
                                    //     if ($taskSettings->enable_milestones) {
                                    //         return true;
                                    //     }

                                    //     $project = Project::find($get('project_id'));

                                    //     if (! $project) {
                                    //         return false;
                                    //     }

                                    //     return $project->allow_milestones;
                                    // }),
                                Forms\Components\Select::make('partner_id')
                                    ->label('Customer')
                                    ->relationship('partner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(fn (Form $form) => PartnerResource::form($form))
                                    ->editOptionForm(fn (Form $form) => PartnerResource::form($form)),
                                Forms\Components\Select::make('user_id')
                                    ->label('Assignees')
                                    ->relationship('users', 'name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload()
                                    ->createOptionForm(fn (Form $form) => UserResource::form($form)),
                                Forms\Components\DateTimePicker::make('deadline')
                                    ->label('Deadline')
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar'),
                                Forms\Components\TextInput::make('allocated_hours')
                                    ->label('Allocated Hours')
                                    ->numeric()
                                    ->suffixIcon('heroicon-o-clock')
                                    ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),
                            ]),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        $isTimesheetEnabled = app(TimeSettings::class)->enable_timesheets;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('priority')
                    ->icon(fn (Task $record): string => $record->priority ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (Task $record): string => $record->priority ? 'warning' : 'gray')
                    ->action(function (Task $record): void {
                        $record->update([
                            'priority' => ! $record->priority,
                        ]);
                    }),
                Tables\Columns\IconColumn::make('state')
                    ->label('State')
                    ->sortable()
                    ->toggleable()
                    ->icon(fn (string $state): string => TaskState::icons()[$state])
                    ->color(fn (string $state): string => TaskState::colors()[$state])
                    ->tooltip(fn (string $state): string => TaskState::options()[$state])
                    ->action(
                        Tables\Actions\Action::make('updateState')
                            ->modalHeading('Update Task State')
                            ->form(fn (Task $record): array => [
                                Forms\Components\ToggleButtons::make('state')
                                    ->label('New State')
                                    ->required()
                                    ->default($record->state)
                                    ->inline()
                                    ->options(TaskState::options())
                                    ->colors(TaskState::colors())
                                    ->icons(TaskState::icons()),
                            ])
                            ->modalSubmitActionLabel('Update State')
                            ->action(function (Task $record, array $data): void {
                                $record->update([
                                    'state' => $data['state'],
                                ]);
                            })
                    ),
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
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (TaskSettings $taskSettings) => $taskSettings->enable_milestones),
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
                    ->formatStateUsing(function ($state) {
                        $hours = floor($state);
                        $minutes = ($state - $hours) * 60;

                        return $hours . ':' . $minutes;
                    })
                    ->summarize(
                        Sum::make()
                            ->label('Allocated Time')
                            ->numeric()
                            ->numeric()
                            ->formatStateUsing(function ($state) {
                                $hours = floor($state);
                                $minutes = ($state - $hours) * 60;

                                return $hours . ':' . $minutes;
                            })
                    )
                    ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),
                Tables\Columns\TextColumn::make('total_hours_spent')
                    ->label('Time Spent')
                    ->sortable()
                    ->toggleable()
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        $hours = floor($state);
                        $minutes = ($state - $hours) * 60;

                        return $hours . ':' . $minutes;
                    })
                    ->summarize(
                        Sum::make()
                            ->label('Time Spent')
                            ->numeric()
                            ->formatStateUsing(function ($state) {
                                $hours = floor($state);
                                $minutes = ($state - $hours) * 60;

                                return $hours . ':' . $minutes;
                            })
                    )
                    ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),
                Tables\Columns\TextColumn::make('remaining_hours')
                    ->label('Time Remaining')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(function ($state) {
                        $hours = floor($state);
                        $minutes = ($state - $hours) * 60;

                        return $hours . ':' . $minutes;
                    })
                    ->summarize(
                        Sum::make()
                            ->label('Time Remaining')
                            ->numeric()
                            ->numeric()
                            ->formatStateUsing(function ($state) {
                                $hours = floor($state);
                                $minutes = ($state - $hours) * 60;

                                return $hours . ':' . $minutes;
                            })
                    )
                    ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),
                ProgressBarEntry::make('progress')
                    ->label('Progress')
                    ->sortable()
                    ->toggleable()
                    ->color(fn (Task $record): string => $record->progress > 100 ? 'danger' : ($record->progress < 100 ? 'warning' : 'success'))
                    ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),
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
                Tables\Grouping\Group::make('state')
                    ->label('State')
                    ->getTitleFromRecordUsing(fn (Task $record): string => TaskState::options()[$record->state]),
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
                    ->constraints(collect([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('title')
                            ->label('Title'),
                        Tables\Filters\QueryBuilder\Constraints\SelectConstraint::make('priority')
                            ->options([
                                0 => 'Low',
                                1 => 'High',
                            ]),
                        Tables\Filters\QueryBuilder\Constraints\SelectConstraint::make('state')
                            ->label('State')
                            ->multiple()
                            ->options(TaskState::options()),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('tags')
                            ->label('Tags')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        $isTimesheetEnabled
                            ? Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('allocated_hours')
                                ->label('Allocated Hours')
                            : null,
                        $isTimesheetEnabled
                            ? Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('total_hours_spent')
                                ->label('Total Hours Spent')
                            : null,
                        $isTimesheetEnabled
                            ? Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('remaining_hours')
                                ->label('Remaining Hours')
                            : null,
                        $isTimesheetEnabled
                            ? Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('overtime')
                                ->label('Overtime')
                            : null,
                        $isTimesheetEnabled
                            ? Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('progress')
                                ->label('Progress')
                            : null,
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('deadline'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('users')
                            ->label('Assignees')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('partner')
                            ->label('Customer')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('project')
                            ->label('Project')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('stage')
                            ->label('Stage')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('milestone')
                            ->label('Milestone')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label('Company')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('creator')
                            ->label('Creator')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                    ])->filter()->values()->all()),
            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
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
            ])
            ->modifyQueryUsing(fn ($query) => $query->whereNull('parent_id'));
    }

    public static function getRelations(): array
    {
        if (! preg_match('/\d+/', request()->getPathInfo(), $matches)) {
            return [];
        }

        $task = Task::find($matches[0]);

        $relations = [];

        if (app(TimeSettings::class)->enable_timesheets && $task?->project?->allow_timesheets) {
            $relations[] = RelationGroup::make('Timesheets', [
                RelationManagers\TimesheetsRelationManager::class,
            ]);
        }

        $relations[] = RelationGroup::make('Sub Tasks', [
            RelationManagers\SubTasksRelationManager::class,
        ]);

        return $relations;
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
