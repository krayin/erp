<?php

namespace Webkul\Employee\Traits\Resources\Employee;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Models\SkillType;
use Webkul\Support\Filament\Tables as CustomTables;

trait EmployeeSkillRelation
{
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

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('skillType.name')
                                    ->placeholder('—')
                                    ->label('Skill Type'),
                                Infolists\Components\TextEntry::make('skill.name')
                                    ->placeholder('—')
                                    ->label('Skill'),
                                Infolists\Components\TextEntry::make('skillLevel.name')
                                    ->placeholder('—')
                                    ->badge()
                                    ->color(fn ($record) => $record->skillType?->color)
                                    ->label('Skill Level'),
                                CustomTables\Infolists\ProgressBarEntry::make('skillLevel.level')
                                    ->getStateUsing(fn ($record) => $record->skillLevel?->level)
                                    ->color(function ($record) {
                                        if ($record->skillLevel->level === 100) {
                                            return 'success';
                                        } elseif ($record->skillLevel->level >= 50 && $record->skillLevel->level < 80) {
                                            return 'warning';
                                        } elseif ($record->skillLevel->level < 20) {
                                            return 'danger';
                                        } else {
                                            return 'info';
                                        }
                                    })
                                    ->label('Level Percent'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan('full'),
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
                    ->color(fn ($record) => $record->skillType?->color),
                CustomTables\Columns\ProgressBarEntry::make('skillLevel.level')
                    ->getStateUsing(fn ($record) => $record->skillLevel?->level)
                    ->color(function ($record) {
                        if ($record->skillLevel->level === 100) {
                            return 'success';
                        } elseif ($record->skillLevel->level >= 50 && $record->skillLevel->level < 80) {
                            return 'warning';
                        } elseif ($record->skillLevel->level < 20) {
                            return 'danger';
                        } else {
                            return 'info';
                        }
                    })
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
                Tables\Actions\ViewAction::make(),
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
