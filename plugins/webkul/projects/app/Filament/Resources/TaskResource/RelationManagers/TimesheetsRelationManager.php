<?php

namespace Webkul\Project\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;

class TimesheetsRelationManager extends RelationManager
{
    protected static string $relationship = 'timesheets';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('type')
                    ->default('projects'),
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('user_id')
                    ->label('Employee')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Description'),
                Forms\Components\TextInput::make('unit_amount')
                    ->label('Time Spent')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->helperText('Time spent in hours (Eg. 1.5 hours means 1 hour 30 minutes)'),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Employee'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Description'),
                Tables\Columns\TextColumn::make('unit_amount')
                    ->label('Time Spent')
                    ->formatStateUsing(function ($state) {
                        $hours = floor($state);
                        $minutes = ($state - $hours) * 60;

                        return $hours.':'.$minutes;
                    })
                    ->summarize([
                        Sum::make()
                            ->label('Total Time Spent')
                            ->formatStateUsing(function ($state) {
                                $hours = floor($state);
                                $minutes = ($state - $hours) * 60;

                                return $hours.':'.$minutes;
                            }),
                        Sum::make()
                            ->label('Remaining Time')
                            ->formatStateUsing(function ($state) {
                                $remainingHours = $this->getOwnerRecord()->allocated_hours - $state;

                                $hours = floor($remainingHours);
                                $minutes = ($remainingHours - $hours) * 60;

                                return $hours.':'.$minutes;
                            })
                            ->visible((bool) $this->getOwnerRecord()->allocated_hours),
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->paginated(false);
    }
}
