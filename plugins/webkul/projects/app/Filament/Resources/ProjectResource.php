<?php

namespace Webkul\Project\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Resources\ProjectResource\Pages;
use Webkul\Project\Filament\Resources\ProjectResource\RelationManagers;
use Webkul\Project\Models\Project;
use Webkul\Project\Models\ProjectStage;
use Webkul\Project\Filament\Clusters\Configurations\Resources\TagResource;
use Webkul\Security\Filament\Resources\UserResource;

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
                                Forms\Components\TextInput::make('tasks_label')
                                    ->label('Tasks Label')
                                    ->maxLength(255)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Name used to refer to the tasks of your project e.g. tasks, tickets, sprints, etc...'),
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
                                Forms\Components\TextInput::make('allocated_hours')
                                    ->label('Allocated Hours')
                                    ->suffixIcon('heroicon-o-clock'),
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
                                    ->default('public')
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
                                            ->helperText('Log time on tasks and track progress'),
                                    ])
                                    ->columns(1),

                                Forms\Components\Fieldset::make('Task Management')
                                    ->schema([
                                        Forms\Components\Toggle::make('allow_milestones')
                                            ->label('Allow Milestones')
                                            ->helperText('Track major progress points that must be reached to achieve success'),
                                        Forms\Components\Toggle::make('allow_task_dependencies')
                                            ->label('Allow Task Dependencies')
                                            ->helperText('Determine the order in which to perform tasks'),
                                    ])
                                    ->columns(1),
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
                    Tables\Columns\TextColumn::make('name')
                        ->weight(FontWeight::Bold),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('start_date')
                            ->icon('heroicon-o-clock')
                            ->formatStateUsing(fn (Project $record): string => $record->start_date->format('d M Y').' - '.$record->end_date->format('d M Y')),
                    ])
                        ->visible(fn (Project $record) => filled($record->start_date) && filled($record->end_date)),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('user.name')
                            ->icon('heroicon-m-user'),
                    ])
                        ->visible(fn (Project $record) => filled($record->user)),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('partner.name')
                            ->icon('heroicon-m-phone'),
                    ])
                        ->visible(fn (Project $record) => filled($record->partner)),
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
                    ->hidden(fn ($record) => $record->trashed())
                    ->url(fn (Project $record): string => route('filament.admin.resources.project.projects.milestones', $record->id)),

                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
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
        return [
            RelationGroup::make('Task Stages', [
                RelationManagers\TaskStagesRelationManager::class,
            ]),

            RelationGroup::make('Milestones', [
                RelationManagers\MilestonesRelationManager::class,
            ]),
        ];
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
