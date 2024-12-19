<?php

namespace Webkul\Project\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Clusters\Configurations\Resources\TagResource;
use Webkul\Project\Filament\Resources\ProjectResource\Pages;
use Webkul\Project\Filament\Resources\ProjectResource\RelationManagers;
use Webkul\Project\Models\Project;
use Webkul\Project\Models\ProjectStage;
use Webkul\Security\Filament\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Settings\TaskSettings;
use Webkul\Project\Settings\TimeSettings;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $slug = 'project/projects';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

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
                            ->visible(fn (TaskSettings $taskSettings) => $taskSettings->enable_project_stages)
                            ->options(fn () => ProjectStage::all()->mapWithKeys(fn ($stage) => [$stage->id => $stage->name]))
                            ->default(ProjectStage::first()?->id),
                        Forms\Components\Section::make('General Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Project Name...')
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                                Forms\Components\Textarea::make('description')
                                    ->label('Description'),
                            ]),

                        Forms\Components\Section::make('Additional Information')
                            ->schema([
                                // Forms\Components\TextInput::make('tasks_label')
                                //     ->label('Tasks Label')
                                //     ->maxLength(255)
                                //     ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Name used to refer to the tasks of your project e.g. tasks, tickets, sprints, etc...'),
                                Forms\Components\Select::make('user_id')
                                    ->label('Project Manager')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(fn (Form $form) => UserResource::form($form)),
                                Forms\Components\Select::make('partner_id')
                                    ->label('Customer')
                                    ->relationship('partner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(fn (Form $form) => PartnerResource::form($form))
                                    ->editOptionForm(fn (Form $form) => PartnerResource::form($form)),
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Start Date')
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->requiredWith('end_date')
                                    ->beforeOrEqual('start_date'),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('End Date')
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->requiredWith('start_date')
                                    ->afterOrEqual('start_date'),
                                Forms\Components\TextInput::make('allocated_hours')
                                    ->label('Allocated Hours')
                                    ->suffixIcon('heroicon-o-clock')
                                    ->minValue(0)
                                    ->helperText('In hours (Eg. 1.5 hours means 1 hour 30 minutes)')
                                    ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),
                                Forms\Components\Select::make('tags')
                                    ->label('Tags')
                                    ->relationship(name: 'tags', titleAttribute: 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(fn (Form $form) => TagResource::form($form)),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Settings')
                            ->schema([
                                Forms\Components\Radio::make('visibility')
                                    ->label('Visibility')
                                    ->default('internal')
                                    ->options([
                                        'private'  => 'Private',
                                        'internal' => 'Internal',
                                        'public'   => 'Public',
                                    ])
                                    ->descriptions([
                                        'private'  => 'Invited internal users only.',
                                        'internal' => 'All internal users can see.',
                                        'public'   => 'Invited portal users and all internal users',
                                    ])
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Grant employees access to your project or tasks by adding them as followers. Employees automatically get access to the tasks they are assigned to.'),

                                Forms\Components\Fieldset::make('Time Customer Management')
                                    ->schema([
                                        Forms\Components\Toggle::make('allow_timesheets')
                                            ->label('Allow Timesheets')
                                            ->helperText('Log time on tasks and track progress')
                                            ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),
                                    ])
                                    ->columns(1)
                                    ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets)
                                    ->default(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),

                                Forms\Components\Fieldset::make('Task Management')
                                    ->schema([
                                        Forms\Components\Toggle::make('allow_milestones')
                                            ->label('Allow Milestones')
                                            ->helperText('Track major progress points that must be reached to achieve success')
                                            ->visible(fn (TaskSettings $taskSettings) => $taskSettings->enable_milestones)
                                            ->default(fn (TaskSettings $taskSettings) => $taskSettings->enable_milestones),
                                        // Forms\Components\Toggle::make('allow_task_dependencies')
                                        //     ->label('Allow Task Dependencies')
                                        //     ->helperText('Determine the order in which to perform tasks'),
                                    ])
                                    ->columns(1)
                                    ->visible(fn (TaskSettings $taskSettings) => $taskSettings->enable_milestones),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->label('Name')
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\IconColumn::make('is_favorite_by_user')
                            ->boolean()
                            ->trueIcon('heroicon-s-star')
                            ->falseIcon('heroicon-o-star')
                            ->trueColor('warning')
                            ->falseColor('gray')
                            ->alignRight()
                            ->action(function (Project $record): void {
                                $record->favoriteUsers()->toggle([Auth::id()]);
                            }),
                    ]),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('partner.name')
                            ->icon('heroicon-m-phone'),
                    ])
                        ->visible(fn (Project $record) => filled($record->partner)),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('start_date')
                            ->icon('heroicon-o-calendar')
                            ->formatStateUsing(fn (Project $record): string => $record->start_date->format('d M Y').' - '.$record->end_date->format('d M Y')),
                    ])
                        ->visible(fn (Project $record) => filled($record->start_date) && filled($record->end_date)),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('remaining_hours')
                            ->icon('heroicon-m-clock')
                            ->badge()
                            ->color('success')
                            ->color(fn (Project $record): string => $record->remaining_hours < 0 ? 'danger' : 'success')
                            ->formatStateUsing(fn (Project $record): string => $record->remaining_hours.' Hours')
                            ->tooltip('Remaining Hours'),
                    ])
                        ->visible(fn (TimeSettings $timeSettings, Project $record) => $timeSettings->enable_timesheets && $record->allow_milestones && $record->remaining_hours),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('user.name')
                            ->icon('heroicon-m-user'),
                    ])
                        ->visible(fn (Project $record) => filled($record->user)),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('tags.name')
                            ->badge()
                            ->weight(FontWeight::Bold),
                    ])
                        ->visible(fn (Project $record): bool => (bool) $record->tags()->get()?->count()),
                ])
                    ->space(3),
            ])
            ->groups([
                Tables\Grouping\Group::make('stage.name')
                    ->label('Stage'),
                Tables\Grouping\Group::make('user.name')
                    ->label('Project Manager'),
                Tables\Grouping\Group::make('partner.name')
                    ->label('Customer'),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label('Name'),
                    ]),
            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\Action::make('tasks')
                    ->label(fn (Project $record): string => $record->tasks->count().' Tasks')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('gray')
                    ->url('https:example.com/tasks/{record}')
                    ->hidden(fn ($record) => $record->trashed())
                    ->url(fn (Project $record): string => route('filament.admin.resources.project.projects.tasks', $record->id)),
                Tables\Actions\Action::make('milestones')
                    ->label(fn (Project $record): string => $record->milestones->where('is_completed', true)->count().'/'.$record->milestones->count())
                    ->icon('heroicon-c-flag')
                    ->color('gray')
                    ->tooltip(fn (Project $record): string => $record->milestones->where('is_completed', true)->count().' milestones completed out of '.$record->milestones->count())
                    ->url('https:example.com/tasks/{record}')
                    ->hidden(fn (Project $record) => $record->trashed())
                    ->visible(fn (TaskSettings $taskSettings, Project $record) => $taskSettings->enable_milestones && $record->allow_milestones)
                    ->url(fn (Project $record): string => route('filament.admin.resources.project.projects.milestones', $record->id)),

                Tables\Actions\EditAction::make()
                    ->hidden(fn (Project $record) => $record->trashed()),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ]);
    }

    public static function getRelations(): array
    {
        if (! preg_match('/\d+/', request()->getPathInfo(), $matches)) {
            return [];
        }

        $project = Project::find($matches[0]);

        $relations = [
            RelationGroup::make('Task Stages', [
                RelationManagers\TaskStagesRelationManager::class,
            ]),
        ];

        if (app(TaskSettings::class)->enable_milestones && $project?->allow_milestones) {
            $relations[] = RelationGroup::make('Milestones', [
                RelationManagers\MilestonesRelationManager::class,
            ]);
        }
        
        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListProjects::route('/'),
            'create'     => Pages\CreateProject::route('/create'),
            'edit'       => Pages\EditProject::route('/{record}/edit'),
            'milestones' => Pages\ManageProjectMilestones::route('/{record}/milestones'),
            'tasks'      => Pages\ManageProjectTasks::route('/{record}/tasks'),
        ];
    }
}
