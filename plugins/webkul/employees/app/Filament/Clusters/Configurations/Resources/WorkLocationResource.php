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
use Webkul\Employee\Enums\WorkLocationEnum;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\WorkLocationResource\Pages;
use Webkul\Employee\Models\WorkLocation;

class WorkLocationResource extends Resource
{
    protected static ?string $model = WorkLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Employee';

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Hidden::make('creator_id')
                    ->required()
                    ->default(Auth::user()->id),
                Forms\Components\ToggleButtons::make('location_type')
                    ->inline()
                    ->options(WorkLocationEnum::class)
                    ->required(),
                Forms\Components\TextInput::make('location_number'),
                Forms\Components\Select::make('company_id')
                    ->searchable()
                    ->required()
                    ->preload()
                    ->relationship('company', 'name'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status')
                    ->required(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name')
                    ->icon('heroicon-o-map')
                    ->placeholder('—')
                    ->label('Name'),
                Infolists\Components\TextEntry::make('location_type')
                    ->icon('heroicon-o-map')
                    ->placeholder('—')
                    ->label('Location Type'),
                Infolists\Components\TextEntry::make('location_number')
                    ->placeholder('—')
                    ->icon('heroicon-o-map')
                    ->label('Location Number'),
                Infolists\Components\TextEntry::make('company.name')
                    ->placeholder('—')
                    ->icon('heroicon-o-building-office')
                    ->label('Country'),
                Infolists\Components\IconEntry::make('is_active')
                    ->boolean()
                    ->label('Status'),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_type')
                    ->badge()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('company.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Job Position')
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label('Created By')
                    ->collapsible(),
                Tables\Grouping\Group::make('location_type')
                    ->label('Location Type')
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label('Company')
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
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label('Name')
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('location_type')
                            ->label('Location Type')
                            ->icon('heroicon-o-map'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('location_number')
                            ->label('Location Number')
                            ->icon('heroicon-o-map'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label('Company')
                            ->icon('heroicon-o-building-office')
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkLocations::route('/'),
        ];
    }
}
