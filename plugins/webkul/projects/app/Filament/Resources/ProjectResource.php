<?php

namespace Webkul\Project\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Project\Enums\ProjectVisibility;
use Webkul\Project\Filament\Clusters\Configurations\Resources\TagResource;
use Webkul\Project\Filament\Resources\ProjectResource\Pages;
use Webkul\Project\Filament\Resources\ProjectResource\RelationManagers;
use Webkul\Project\Models\Project;
use Webkul\Project\Models\ProjectStage;
use Webkul\Project\Settings\TaskSettings;
use Webkul\Project\Settings\TimeSettings;
use Webkul\Security\Filament\Resources\UserResource;

class ProjectResource extends Resource
{
    use HasCustomFields;

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
                            ->schema(static::mergeCustomFormFields([
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
                            ]))
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
                                    ->options(ProjectVisibility::options())
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
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->label('Name')
                            ->searchable()
                            ->sortable(),
                    ]),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('partner.name')
                            ->icon('heroicon-o-phone')
                            ->tooltip('Customer')
                            ->sortable(),
                    ])
                        ->visible(fn (Project $record) => filled($record->partner)),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('start_date')
                            ->sortable()
                            ->extraAttributes(['class' => 'hidden']),
                        Tables\Columns\TextColumn::make('end_date')
                            ->sortable()
                            ->extraAttributes(['class' => 'hidden']),
                        Tables\Columns\TextColumn::make('planned_date')
                            ->icon('heroicon-o-calendar')
                            ->tooltip('Planned Date')
                            ->state(fn (Project $record): string => $record->start_date->format('d M Y').' - '.$record->end_date->format('d M Y')),
                    ])
                        ->visible(fn (Project $record) => filled($record->start_date) && filled($record->end_date)),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('remaining_hours')
                            ->icon('heroicon-o-clock')
                            ->badge()
                            ->color('success')
                            ->color(fn (Project $record): string => $record->remaining_hours < 0 ? 'danger' : 'success')
                            ->state(fn (Project $record): string => $record->remaining_hours.' Hours')
                            ->tooltip('Remaining Hours'),
                    ])
                        ->visible(fn (TimeSettings $timeSettings, Project $record) => $timeSettings->enable_timesheets && $record->allow_milestones && $record->remaining_hours),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('user.name')
                            ->icon('heroicon-o-user')
                            ->tooltip('Project Manager')
                            ->sortable(),
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
            ]))
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
                    ->constraints(static::mergeCustomTableQueryBuilderConstraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label('Name'),
                        Tables\Filters\QueryBuilder\Constraints\SelectConstraint::make('visibility')
                            ->label('Visibility')
                            ->multiple()
                            ->options(ProjectVisibility::options()),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('start_date')
                            ->label('Start Date'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('end_date')
                            ->label('End Date'),
                        Tables\Filters\QueryBuilder\Constraints\BooleanConstraint::make('allow_timesheets')
                            ->label('Allow Timesheets'),
                        Tables\Filters\QueryBuilder\Constraints\BooleanConstraint::make('allow_milestones')
                            ->label('Allow Milestones'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('allocated_hours')
                            ->label('Allocated Hours'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label('Created At'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label('Updated At'),
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
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('user')
                            ->label('Project Manager')
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
                    ])),
            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\Action::make('is_favorite_by_user')
                    ->hiddenLabel()
                    ->icon(fn (Project $record): string => $record->is_favorite_by_user ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (Project $record): string => $record->is_favorite_by_user ? 'warning' : 'gray')
                    ->size('xl')
                    ->action(function (Project $record): void {
                        $record->favoriteUsers()->toggle([Auth::id()]);
                    }),
                Tables\Actions\Action::make('tasks')
                    ->label(fn (Project $record): string => $record->tasks->count().' Tasks')
                    ->icon('heroicon-m-clipboard-document-list')
                    ->color('gray')
                    ->url('https:example.com/tasks/{record}')
                    ->hidden(fn ($record) => $record->trashed())
                    ->url(fn (Project $record): string => route('filament.admin.resources.project.projects.tasks', $record->id)),
                Tables\Actions\Action::make('milestones')
                    ->label(fn (Project $record): string => $record->milestones->where('is_completed', true)->count().'/'.$record->milestones->count())
                    ->icon('heroicon-m-flag')
                    ->color('gray')
                    ->tooltip(fn (Project $record): string => $record->milestones->where('is_completed', true)->count().' milestones completed out of '.$record->milestones->count())
                    ->url('https:example.com/tasks/{record}')
                    ->hidden(fn (Project $record) => $record->trashed())
                    ->visible(fn (TaskSettings $taskSettings, Project $record) => $taskSettings->enable_milestones && $record->allow_milestones)
                    ->url(fn (Project $record): string => route('filament.admin.resources.project.projects.milestones', $record->id)),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->hidden(fn (Project $record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->link()
                    ->hiddenLabel(),
            ])
            ->recordUrl(fn (Project $record): string => static::getUrl('view', ['record' => $record]))
            ->contentGrid([
                'sm'  => 1,
                'md'  => 2,
                'xl'  => 3,
                '2xl' => 4,
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make('General Information')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Name')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(\Filament\Support\Enums\FontWeight::Bold),

                                Infolists\Components\TextEntry::make('description')
                                    ->label('Description')
                                    ->markdown(),
                            ]),

                        Infolists\Components\Section::make('Additional Information')
                            ->schema(static::mergeCustomInfolistEntries([
                                Infolists\Components\Grid::make(2)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('user.name')
                                            ->label('Project Manager')
                                            ->icon('heroicon-o-user')
                                            ->placeholder('—'),

                                        Infolists\Components\TextEntry::make('partner.name')
                                            ->label('Customer')
                                            ->icon('heroicon-o-phone')
                                            ->placeholder('—'),

                                        Infolists\Components\TextEntry::make('planned_date')
                                            ->label('Project Timeline')
                                            ->icon('heroicon-o-calendar')
                                            ->state(function (Project $record): ?string {
                                                if (! $record->start_date || ! $record->end_date) {
                                                    return '—';
                                                }

                                                return $record->start_date->format('d M Y').' - '.$record->end_date->format('d M Y');
                                            }),

                                        Infolists\Components\TextEntry::make('allocated_hours')
                                            ->label('Allocated Hours')
                                            ->icon('heroicon-o-clock')
                                            ->placeholder('—')
                                            ->suffix(' Hours')
                                            ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),

                                        Infolists\Components\TextEntry::make('remaining_hours')
                                            ->label('Remaining Hours')
                                            ->icon('heroicon-o-clock')
                                            ->suffix(' Hours')
                                            ->color(fn (Project $record): string => $record->remaining_hours < 0 ? 'danger' : 'success')
                                            ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),

                                        Infolists\Components\TextEntry::make('stage.name')
                                            ->label('Current Stage')
                                            ->icon('heroicon-o-flag')
                                            ->badge()
                                            ->visible(fn (TaskSettings $taskSettings) => $taskSettings->enable_project_stages),

                                        Infolists\Components\TextEntry::make('tags.name')
                                            ->label('Tags')
                                            ->badge()
                                            ->separator(', ')
                                            ->weight(\Filament\Support\Enums\FontWeight::Bold),
                                    ]),
                            ])),

                        Infolists\Components\Section::make('Statistics')
                            ->schema([
                                Infolists\Components\Grid::make(2)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('tasks_count')
                                            ->label('Total Tasks')
                                            ->state(fn (Project $record): int => $record->tasks()->count())
                                            ->icon('heroicon-m-clipboard-document-list')
                                            ->iconColor('primary')
                                            ->color('primary')
                                            ->url(fn (Project $record): string => route('filament.admin.resources.project.projects.tasks', $record->id)),

                                        Infolists\Components\TextEntry::make('milestones_completion')
                                            ->label('Milestones Progress')
                                            ->state(function (Project $record): string {
                                                $completed = $record->milestones()->where('is_completed', true)->count();
                                                $total = $record->milestones()->count();

                                                return "{$completed}/{$total}";
                                            })
                                            ->icon('heroicon-m-flag')
                                            ->iconColor('primary')
                                            ->color('primary')
                                            ->url(fn (Project $record): string => route('filament.admin.resources.project.projects.milestones', $record->id))
                                            ->visible(fn (TaskSettings $taskSettings, Project $record) => $taskSettings->enable_milestones && $record->allow_milestones),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make('Record Details')
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label('Created By')
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar-days'),
                            ]),

                        Infolists\Components\Section::make('Project Settings')
                            ->schema([
                                Infolists\Components\TextEntry::make('visibility')
                                    ->label('Visibility')
                                    ->badge()
                                    ->icon(fn (string $state): string => ProjectVisibility::icons()[$state])
                                    ->color(fn (string $state): string => ProjectVisibility::colors()[$state])
                                    ->formatStateUsing(fn (string $state): string => ProjectVisibility::options()[$state]),

                                Infolists\Components\IconEntry::make('allow_timesheets')
                                    ->label('Timesheets Enabled')
                                    ->boolean()
                                    ->visible(fn (TimeSettings $timeSettings) => $timeSettings->enable_timesheets),

                                Infolists\Components\IconEntry::make('allow_milestones')
                                    ->label('Milestones Enabled')
                                    ->boolean()
                                    ->visible(fn (TaskSettings $taskSettings) => $taskSettings->enable_milestones),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getRelations(): array
    {
        $relations = [
            RelationGroup::make('Task Stages', [
                RelationManagers\TaskStagesRelationManager::class,
            ]),
        ];

        if (app(TaskSettings::class)->enable_milestones) {
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
            'view'       => Pages\ViewProject::route('/{record}'),
            'milestones' => Pages\ManageProjectMilestones::route('/{record}/milestones'),
            'tasks'      => Pages\ManageProjectTasks::route('/{record}/tasks'),
        ];
    }
}
