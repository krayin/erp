<?php

namespace Webkul\Project\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Webkul\Project\Enums\TaskState;
use Filament\Tables\Table;
use Webkul\Project\Models\Task;

class SubTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'subTasks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('state')
                    ->required()
                    ->default('in_progress')
                    ->inline()
                    ->options(TaskState::options())
                    ->colors(TaskState::colors())
                    ->icons(TaskState::icons()),
                Forms\Components\DateTimePicker::make('deadline')
                    ->label('Deadline')
                    ->native(false),
                Forms\Components\Select::make('user_id')
                    ->label('Assignees')
                    ->relationship('users', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('priority')
                    ->icon(fn (Task $record): string => $record->priority ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (Task $record): string => $record->priority ? 'warning' : 'gray')
                    ->action(
                        Tables\Actions\Action::make('select')
                            ->action(function (Task $record): void {
                                $record->update([
                                    'priority' => ! $record->priority,
                                ]);
                            }),
                    ),
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
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->toggleable(isToggledHiddenByDefault: true)
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
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Deadline')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stage.name')
                    ->label('Stage')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
