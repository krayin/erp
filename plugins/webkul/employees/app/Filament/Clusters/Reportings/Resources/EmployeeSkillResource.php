<?php

namespace Webkul\Employee\Filament\Clusters\Reportings\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Clusters\Reportings;
use Webkul\Employee\Filament\Clusters\Reportings\Resources\EmployeeSkillResource\Pages;
use Webkul\Employee\Filament\Tables as CustomTables;
use Webkul\Employee\Models\EmployeeSkill;

class EmployeeSkillResource extends Resource
{
    protected static ?string $model = EmployeeSkill::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $modelLabel = 'Skills';

    protected static ?string $cluster = Reportings::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('skill.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('skillLevel.name')
                    ->numeric()
                    ->sortable(),
                CustomTables\Columns\ProgressBarEntry::make('skillLevel.level')
                    ->getStateUsing(fn ($record) => $record->skillLevel->level)
                    ->label('Level Percent'),
                Tables\Columns\TextColumn::make('skillType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('employee.name')
                    ->label('Name')
                    ->collapsible(),
            ])
            ->defaultGroup('employee.name')
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEmployeeSkills::route('/'),
        ];
    }
}
