<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackageTypeResource\Pages;
use Webkul\Inventory\Models\PackageType;

class PackageTypeResource extends Resource
{
    protected static ?string $model = PackageType::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 10;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/package-type.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/package-type.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),

                        Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.fieldsets.size.title'))
                            ->schema([
                                Forms\Components\TextInput::make('length')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.fieldsets.size.fields.length'))
                                    ->required()
                                    ->numeric()
                                    ->default(0.0000)
                                    ->minValue(0),
                                Forms\Components\TextInput::make('width')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.fieldsets.size.fields.width'))
                                    ->required()
                                    ->numeric()
                                    ->default(0.0000)
                                    ->minValue(0),
                                Forms\Components\TextInput::make('height')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.fieldsets.size.fields.height'))
                                    ->required()
                                    ->numeric()
                                    ->default(0.0000)
                                    ->minValue(0),
                            ])
                            ->columns(3),
                        Forms\Components\TextInput::make('base_weight')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.weight'))
                            ->required()
                            ->numeric()
                            ->default(0.0000),
                        Forms\Components\TextInput::make('max_weight')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.max-weight'))
                            ->required()
                            ->numeric()
                            ->default(0.0000),
                        Forms\Components\TextInput::make('barcode')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.barcode')),
                        Forms\Components\Select::make('company_id')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.company'))
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('height')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.height'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('width')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.width'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('length')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.length'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.barcode'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/package-type.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/package-type.table.actions.delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/package-type.table.bulk-actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/package-type.table.bulk-actions.delete.notification.body')),
                    ),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
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
            'index'  => Pages\ListPackageTypes::route('/'),
            'create' => Pages\CreatePackageType::route('/create'),
            'view'   => Pages\ViewPackageType::route('/{record}'),
            'edit'   => Pages\EditPackageType::route('/{record}/edit'),
        ];
    }
}
