<?php

namespace Webkul\Employee\Filament\Clusters\Reportings\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Clusters\Reportings;
use Webkul\Employee\Filament\Clusters\Reportings\Resources\EmployeeSkillResource\Pages;
use Webkul\Employee\Models\EmployeeSkill;
use Webkul\Support\Filament\Tables as CustomTables;

class EmployeeSkillResource extends Resource
{
    protected static ?string $model = EmployeeSkill::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $modelLabel = 'Skills';

    protected static ?string $pluralModelLabel = 'Skills';

    protected static ?string $cluster = Reportings::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Skill Details')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Employee')
                            ->relationship('employee', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('skill_id')
                            ->label('Skill')
                            ->relationship('skill', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('skill_level_id')
                            ->label('Skill Level')
                            ->relationship('skillLevel', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('skill_type_id')
                            ->label('Skill Type')
                            ->relationship('skillType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Select::make('creator_id')
                            ->label('Created By')
                            ->relationship('creator', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->label('Updated By')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('skill.name')
                    ->label('Skill')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('skillLevel.name')
                    ->label('Skill Level')
                    ->badge()
                    ->color(fn ($record) => match ($record->skillLevel->name) {
                        'Beginner'     => 'gray',
                        'Intermediate' => 'warning',
                        'Advanced'     => 'success',
                        'Expert'       => 'primary',
                        default        => 'secondary'
                    }),
                CustomTables\Columns\ProgressBarEntry::make('skill_level_percentage')
                    ->label('Proficiency')
                    ->getStateUsing(fn ($record) => $record->skillLevel->level ?? 0),
                Tables\Columns\TextColumn::make('skillType.name')
                    ->label('Skill Type')
                    ->badge()
                    ->color('secondary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->groups([
                Tables\Grouping\Group::make('employee.name')
                    ->label('Employee')
                    ->collapsible(),
                Tables\Grouping\Group::make('skillType.name')
                    ->label('Skill Type')
                    ->collapsible(),
            ])
            ->defaultGroup('employee.name')
            ->filtersFormColumns(2)
            ->filters([
                SelectFilter::make('employee')
                    ->relationship('employee', 'name')
                    ->preload()
                    ->searchable()
                    ->label('Employee'),
                SelectFilter::make('skill')
                    ->relationship('skill', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Skill'),
                SelectFilter::make('skill_level')
                    ->relationship('skillLevel', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Skill Level'),
                SelectFilter::make('skill_type')
                    ->relationship('skillType', 'name')
                    ->preload()
                    ->searchable()
                    ->label('Skill Type'),
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label('Name')
                            ->icon('heroicon-o-building-office-2'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('employee')
                            ->label('Employee')
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('creator')
                            ->label('Created By')
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('user')
                            ->label('User')
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You can add related resources if needed
        ];
    }

    public static function getSlug(): string
    {
        return 'employees/skills';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeSkills::route('/'),
        ];
    }
}
