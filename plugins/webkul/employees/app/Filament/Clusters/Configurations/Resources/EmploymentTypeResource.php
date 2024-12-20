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
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;
use Webkul\Employee\Models\EmploymentType;

class EmploymentTypeResource extends Resource
{
    protected static ?string $model = EmploymentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Recruitment';

    public static function getModelLabel(): string
    {
        return 'Employment Type';
    }

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('creator_id')
                    ->default(Auth::user()->id),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('code')
                    ->label('Code'),
                Forms\Components\Select::make('country_id')
                    ->searchable()
                    ->preload()
                    ->relationship('country', 'name'),
            ])
            ->columns(2);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name')
                    ->icon('heroicon-o-user')
                    ->label('Name'),
                Infolists\Components\TextEntry::make('code')
                    ->icon('heroicon-o-user')
                    ->label('Code'),
                Infolists\Components\TextEntry::make('country.name')
                    ->icon('heroicon-o-map')
                    ->label('Country'),
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
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label('Code'),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->searchable()
                    ->label('Country'),
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
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('code')
                    ->label('Code')
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label('Created By')
                    ->collapsible(),
                Tables\Grouping\Group::make('country.name')
                    ->label('Country')
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
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label('Name')
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('country')
                            ->label('Country')
                            ->icon('heroicon-o-map')
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
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sort'] = EmploymentType::max('sort') + 1;

                        $data['code'] = $data['code'] ?? $data['name'];

                        return $data;
                    }),
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
            ])
            ->reorderable('sort');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmploymentTypes::route('/'),
        ];
    }
}
