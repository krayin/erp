<?php

namespace Webkul\Project\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Project\Filament\Resources\TaskResource\Pages;
use Webkul\Project\Filament\Resources\TaskResource\RelationManagers;
use Webkul\Project\Models\Task;

class TaskResource extends Resource
{
    use HasCustomFields;

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
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
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
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_hours_spent')
                    ->label('Time Spent')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('remaining_hours')
                    ->label('Time Remaining')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->filters([
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
            ->modifyQueryUsing(function ($query) {
                $query->whereNull('parent_id');
            });
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

    public static function getSlug(): string
    {
        return 'project/tasks';
    }
}
