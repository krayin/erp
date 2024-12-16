<?php

namespace Webkul\Project\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;

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
