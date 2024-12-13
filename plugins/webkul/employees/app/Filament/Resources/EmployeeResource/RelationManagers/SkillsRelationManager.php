<?php

namespace Webkul\Employee\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Models\Skill;
use Webkul\Employee\Models\SkillLevel;
use Webkul\Employee\Models\SkillType;

class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'skills';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('skill_type_id')
                    ->label('Skill Type')
                    ->options(SkillType::pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($set) => $set('skill_id', null)),
                Forms\Components\Select::make('skill_id')
                    ->label('Skill')
                    ->options(function (callable $get) {
                        $skillTypeId = $get('skill_type_id');

                        return $skillTypeId
                            ? Skill::where('skill_type_id', $skillTypeId)->pluck('name', 'id')
                            : [];
                    })
                    ->required()
                    ->disabled(fn (callable $get) => ! $get('skill_type_id')),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('skillType.name')
                    ->label('Skill Type')
                    ->sortable(),

                // Tables\Columns\TextColumn::make('name')
                //     ->label('Skill')
                //     ->sortable(),

                // Tables\Columns\TextColumn::make('skillLevel.name')
                //     ->label('Skill Level')
                //     ->sortable(),

                // Tables\Columns\TextColumn::make('years_of_experience')
                //     ->label('Experience')
                //     ->sortable(),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('skill_type_id')
                //     ->label('Skill Type')
                //     ->relationship('skillType', 'name'),

                // Tables\Filters\SelectFilter::make('skill_level_id')
                //     ->label('Skill Level')
                //     ->relationship('skillLevel', 'name'),
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
