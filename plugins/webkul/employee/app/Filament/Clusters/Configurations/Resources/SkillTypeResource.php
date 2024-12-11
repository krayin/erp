<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                Forms\Components\Section::make('Skill Type Details')
                    ->description('Create and manage skill type.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Skill Type')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Enter skill type name'),
                        Forms\Components\Select::make('color')
                            ->label('Color')
                            ->required()
                            ->options(function () {
                                return collect([
                                    'danger'  => 'Danger',
                                    'gray'    => 'Gray',
                                    'info'    => 'Info',
                                    'success' => 'Success',
                                    'warning' => 'Warning',
                                ])->mapWithKeys(function ($value, $key) {
                                    return [
                                        $key => '<div class="flex items-center gap-4"><span class="flex h-5 w-5 rounded-full" style="background: rgb(var(--'.$key.'-500))"></span> '.$value.'</span>',
                                    ];
                                });
                            })
                            ->native(false)
                            ->allowHtml(),
                        Forms\Components\Toggle::make('status')
                            ->label('Active Status')
                            ->default(true),
                    ])->columns(2),
            ])
            ->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Skill Type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Color')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn (SkillType $skillType) => '<span class="flex h-5 w-5 border-2 border-gray-600" style="background: rgb(var(--'.$skillType->color.'-500))"></span>')
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
                Tables\Columns\IconColumn::make('status')
                    ->sortable()
                    ->label('Status')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
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
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
