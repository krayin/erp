<?php

namespace Webkul\Project\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Resources\ProjectResource\Pages;
use Webkul\Project\Models\Project;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Project';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('General Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
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
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('email')
                                            ->required()
                                            ->email(),
                                    ]),
                                Forms\Components\Select::make('partner_id')
                                    ->label('Customer')
                                    ->relationship('partner', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\DatePicker::make('start_date')
                                    ->native(false),
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
                                Forms\Components\TextInput::make('allocated_hours')
                                    ->label('Allocated Hours'),
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
                    Tables\Columns\TextColumn::make('plannedDate')
                        ->icon('heroicon-o-clock'),
                    Tables\Columns\TextColumn::make('user.name')
                        ->icon('heroicon-m-user'),
                    Tables\Columns\TextColumn::make('partner.name')
                        ->icon('heroicon-m-phone'),
                    Tables\Columns\TextColumn::make('tags.name')
                        ->badge()
                        ->weight(FontWeight::Bold),
                ])
                    ->space(3),
            ])
            ->groups([
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
                    ->label('1 Tasks')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('primary')
                    ->url('https:example.com/tasks/{record}')
                    ->hidden(fn ($record) => $record->trashed()),
                // ->url(fn (Project $record): string => route('projects.tasks.index', $record)),
                Tables\Actions\Action::make('milestones')
                    ->label('0/1')
                    ->icon('heroicon-c-flag')
                    ->color('gray')
                    ->tooltip('0 milestones reached out of 10')
                    ->url('https:example.com/tasks/{record}')
                    ->hidden(fn ($record) => $record->trashed()),
                // ->url(fn (Project $record): string => route('projects.tasks.index', $record)),
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

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('allocated_hours')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('allow_timesheets')
                    ->boolean(),
                Tables\Columns\IconColumn::make('allow_milestones')
                    ->boolean(),
                Tables\Columns\IconColumn::make('allow_task_dependencies')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('stage.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
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
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit'   => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getSlug(): string
    {
        return 'project/projects';
    }
}
