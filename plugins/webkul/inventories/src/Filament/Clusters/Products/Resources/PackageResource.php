<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Products;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource\Pages;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource\RelationManagers;
use Webkul\Inventory\Models\Package;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $cluster = Products::class;

    protected static ?int $navigationSort = 2;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/package.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('inventories::filament/clusters/products/resources/package.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->placeholder(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.name-placeholder'))
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('package_type_id')
                                    ->label(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.package-type'))
                                    ->relationship('packageType', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\DatePicker::make('pack_date')
                                    ->label(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.pack-date'))
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->default(today()),
                                Forms\Components\Select::make('location_id')
                                    ->label(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.location'))
                                    ->relationship('location', 'full_name')
                                    ->searchable()
                                    ->preload(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('packageType.name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.columns.package-type'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.full_name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.columns.location'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.columns.company'))
                    ->searchable()
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('packageType.name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.groups.package-type')),
                Tables\Grouping\Group::make('location.full_name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.groups.location')),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.groups.created-at'))
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/products/resources/package.table.actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/products/resources/package.table.actions.delete.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/products/resources/package.table.bulk-actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/products/resources/package.table.bulk-actions.delete.notification.body')),
                    ),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPackage::class,
            Pages\EditPackage::class,
            Pages\ManageProducts::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListPackages::route('/'),
            'create'     => Pages\CreatePackage::route('/create'),
            'edit'       => Pages\EditPackage::route('/{record}/edit'),
            'view'       => Pages\ViewPackage::route('/{record}/view'),
            'products'   => Pages\ManageProducts::route('/{record}/products'),
        ];
    }
}
