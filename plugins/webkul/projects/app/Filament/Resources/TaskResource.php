<?php

namespace Webkul\Project\Filament\Resources;

use Webkul\Project\Filament\Resources\TaskResource\Pages;
use Webkul\Project\Filament\Resources\TaskResource\RelationManagers;
use Webkul\Project\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Project';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('General Information')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\ToggleButtons::make('state')
                                    ->required()
                                    ->default('in_progress')
                                    ->inline()
                                    ->options([
                                        'in_progress' => 'In Progress',
                                        'change_requested' => 'Change Requested',
                                        'approved' => 'Approved',
                                        'cancelled' => 'Cancelled',
                                        'done' => 'Done',
                                    ])
                                    ->colors([
                                        'in_progress' => 'gray',
                                        'change_requested' => 'warning',
                                        'approved' => 'success',
                                        'cancelled' => 'danger',
                                        'done' => 'success',
                                    ])
                                    ->icons([
                                        'in_progress' => 'heroicon-m-play-circle',
                                        'change_requested' => 'heroicon-s-exclamation-circle',
                                        'approved' => 'heroicon-o-check-circle',
                                        'cancelled' => 'heroicon-s-x-circle',
                                        'done' => 'heroicon-c-check-circle',
                                    ]),
                                Forms\Components\Select::make('tags')
                                    ->label('Tags')
                                    ->relationship(name: 'tags', titleAttribute: 'name')
                                    ->multiple()
                                    ->searchable()
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
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('color')
                    ->maxLength(255),
                Forms\Components\TextInput::make('priority')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tags'),
                Forms\Components\TextInput::make('sort')
                    ->numeric(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
                Forms\Components\Toggle::make('is_recurring')
                    ->required(),
                Forms\Components\DateTimePicker::make('deadline'),
                Forms\Components\TextInput::make('working_hours_open')
                    ->numeric(),
                Forms\Components\TextInput::make('working_hours_close')
                    ->numeric(),
                Forms\Components\TextInput::make('allocated_hours')
                    ->numeric(),
                Forms\Components\TextInput::make('remaining_hours')
                    ->numeric(),
                Forms\Components\TextInput::make('effective_hours')
                    ->numeric(),
                Forms\Components\TextInput::make('total_hours_spent')
                    ->numeric(),
                Forms\Components\TextInput::make('overtime')
                    ->numeric(),
                Forms\Components\TextInput::make('progress')
                    ->numeric(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name'),
                Forms\Components\Select::make('stage_id')
                    ->relationship('stage', 'name'),
                Forms\Components\Select::make('partner_id')
                    ->relationship('partner', 'name'),
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'title'),
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name'),
                Forms\Components\Select::make('creator_id')
                    ->relationship('creator', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_recurring')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('working_hours_open')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('working_hours_close')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('allocated_hours')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_hours')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('effective_hours')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_hours_spent')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overtime')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
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
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
