<?php

namespace Webkul\Employee\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Models\SkillType;
use Webkul\Support\Filament\Tables as CustomTables;

class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'skills';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Radio::make('skill_type_id')
                        ->label('Skill Type')
                        ->options(SkillType::pluck('name', 'id'))
                        ->default(fn () => SkillType::first()?->id)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('skill_id', null)),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Select::make('skill_id')
                                ->label('Skill')
                                ->options(
                                    fn (callable $get) => SkillType::find($get('skill_type_id'))?->skills->pluck('name', 'id') ?? []
                                )
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('skill_level_id', null)),
                            Forms\Components\Select::make('skill_level_id')
                                ->label('Skill Level')
                                ->options(
                                    fn (callable $get) => SkillType::find($get('skill_type_id'))?->skillLevels->pluck('name', 'id') ?? []
                                )
                                ->required(),
                        ]),
                ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('skillType.name')
                    ->label('Skill Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('skill.name')
                    ->label('Skill')
                    ->sortable(),
                Tables\Columns\TextColumn::make('skillLevel.name')
                    ->label('Skill Level')
                    ->badge()
                    ->color(fn ($record) => $record->skillType->color),
                CustomTables\Columns\ProgressBarEntry::make('skillLevel.level')
                    ->getStateUsing(fn ($record) => $record->skillLevel->level)
                    ->label('Level Percent'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Creator')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date(),
            ])
            ->groups([
                Tables\Grouping\Group::make('skillType.name')
                    ->label('Skill Type')
                    ->collapsible(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Skill')
                    ->icon('heroicon-o-plus-circle'),
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
