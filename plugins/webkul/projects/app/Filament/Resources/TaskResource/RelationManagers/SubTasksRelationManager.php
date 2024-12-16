<?php

namespace Webkul\Project\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Tables\Columns\TextColumn::make('title')
                    ->label('Title'),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Deadline'),
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Assignees'),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
