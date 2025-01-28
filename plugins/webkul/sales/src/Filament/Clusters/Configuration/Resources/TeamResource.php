<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources;

use Webkul\Sale\Filament\Clusters\Configuration;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\TeamResource\Pages;
use Webkul\Sale\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Sales Team');
    }

    public static function getNavigationLabel(): string
    {
        return __('Sales Teams');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'company.name',
            'user.name',
            'name',
            'invoiced_target',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Company') => $record->company?->name ?? '—',
            __('User') => $record->user?->name ?? '—',
            __('Name') => $record->name ?? '—',
            __('Invoiced Target') => $record->invoiced_target ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Sales Team')
                                    ->maxLength(255)
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;'])
                                    ->columnSpan(1),
                            ])->columns(2),
                        Forms\Components\Fieldset::make('Team Details')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->preload()
                                    ->label(__('Team Leader'))
                                    ->searchable(),
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->preload()
                                    ->label('Company')
                                    ->searchable(),
                                Forms\Components\TextInput::make('invoiced_target')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Invoiced Target')
                                    ->autocomplete(false)
                                    ->suffix(__('/ Month')),
                                Forms\Components\ColorPicker::make('color')
                                    ->label('Color'),
                                Forms\Components\Select::make('sales_team_members')
                                    ->relationship('members', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->label('Members'),
                            ])->columns(2),
                        Forms\Components\Toggle::make('is_active')
                            ->inline(false)
                            ->label('Status')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('Company'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Team Leader'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('Color'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('creator_id')
                    ->label(__('Created By'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Status'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('invoiced_target')
                    ->numeric()
                    ->label(__('Invoiced Target'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('Created At'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Updated At'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label(__('Name'))
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('user')
                            ->label(__('Team Leader'))
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('TEam Leader'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label(__('Company'))
                            ->icon('heroicon-o-building-office-2')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Company'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('Created At')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('Created At')),
                    ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('Name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label(__('Company'))
                    ->collapsible(),
                Tables\Grouping\Group::make('user.name')
                    ->label(__('Team Leader'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('Created At'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('Update At'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'view'   => Pages\ViewTeam::route('/{record}'),
            'edit'   => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
