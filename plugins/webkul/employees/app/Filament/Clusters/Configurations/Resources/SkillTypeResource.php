<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Enums;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\RelationManagers;
use Webkul\Employee\Models\SkillType;

class SkillTypeResource extends Resource
{
    protected static ?string $model = SkillType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Employee';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Skill Type')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->placeholder('Enter skill type name'),
                    Forms\Components\Hidden::make('creator_id')
                        ->default(Auth::user()->id),
                    Forms\Components\Select::make('color')
                        ->label('Color')
                        ->options(function () {
                            return collect(Enums\Colors::options())->mapWithKeys(function ($value, $key) {
                                return [
                                    $key => '<div class="flex items-center gap-4"><span class="flex h-5 w-5 rounded-full" style="background: rgb(var(--'.$key.'-500))"></span> '.$value.'</span>',
                                ];
                            });
                        })
                        ->native(false)
                        ->allowHtml(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Status')
                        ->default(true),
                ])->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Skill Type'),
                        Infolists\Components\TextEntry::make('color')
                            ->html()
                            ->formatStateUsing(fn (SkillType $skillType) => '<span class="flex h-5 w-5 rounded-full" style="background: rgb(var(--'.$skillType->color.'-500))"></span>')
                            ->label('Color'),
                        Infolists\Components\IconEntry::make('is_active')
                            ->boolean()
                            ->label('Status'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Skill Type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Color')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn (SkillType $skillType) => '<span class="flex h-5 w-5 rounded-full" style="background: rgb(var(--'.$skillType->color.'-500))"></span>')
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('skills.name')
                    ->label('Skills')
                    ->badge()
                    ->color(fn (SkillType $skillType) => $skillType->color)
                    ->searchable(),
                Tables\Columns\TextColumn::make('skillLevels.name')
                    ->label('Levels')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->label('Status')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->columnToggleFormColumns(2)
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('skillLevels')
                            ->label('Skill Levels')
                            ->icon('heroicon-o-bolt')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('skills')
                            ->label('Skill')
                            ->icon('heroicon-o-bolt')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('createdBy')
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
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at'),
                    ]),
            ])
            ->filtersFormColumns(2)
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Job Position')
                    ->collapsible(),
                Tables\Grouping\Group::make('color')
                    ->label('Color')
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label('Created By')
                    ->collapsible(),
                Tables\Grouping\Group::make('is_active')
                    ->label('Status')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label('Update At')
                    ->date()
                    ->collapsible(),
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
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SkillsRelationManager::class,
            RelationManagers\SkillLevelRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSkillTypes::route('/'),
            'view'  => Pages\ViewSkillType::route('/{record}'),
            'edit'  => Pages\EditSkillType::route('/{record}/edit'),
        ];
    }
}
