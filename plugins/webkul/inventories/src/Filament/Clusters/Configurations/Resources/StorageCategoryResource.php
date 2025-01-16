<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums\AllowNewProduct;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\Pages;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\RelationManagers;
use Webkul\Inventory\Models\StorageCategory;

class StorageCategoryResource extends Resource
{
    protected static ?string $model = StorageCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 4;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/storage-category.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/storage-category.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('max_weight')
                            ->label(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.fields.max-weight'))
                            ->numeric()
                            ->default(0.0000),
                        Forms\Components\Select::make('allow_new_products')
                            ->label(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.fields.allow-new-products'))
                            ->options(AllowNewProduct::class)
                            ->required()
                            ->default(AllowNewProduct::MIXED),
                        Forms\Components\Select::make('company_id')
                            ->label(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.fields.company'))
                            ->relationship(name: 'company', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->default(Auth::user()->default_company_id),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('allow_new_products')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.allow-new-products'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_weight')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.max-weight'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.company'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('allow_new_products')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.groups.allow-new-products'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
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
                            ->title(__('inventories::filament/clusters/configurations/resources/storage-category.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/storage-category.table.actions.delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/storage-category.table.bulk-actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/storage-category.table.bulk-actions.delete.notification.body')),
                    ),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        $currentRoute = request()->route()?->getName();

        if (in_array($currentRoute, [self::getRouteBaseName().'.index', self::getRouteBaseName().'.create'])) {
            return SubNavigationPosition::Start;
        }

        return SubNavigationPosition::Top;
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewStorageCategory::class,
            Pages\EditStorageCategory::class,
            Pages\ManageCapacityByPackages::class,
            Pages\ManageCapacityByProducts::class,
            Pages\ManageLocations::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Capacity By Packages', [
                RelationManagers\CapacityByPackagesRelationManager::class,
            ])
                ->icon('heroicon-o-cube'),

            RelationGroup::make('Capacity By Products', [
                RelationManagers\CapacityByProductsRelationManager::class,
            ])
                ->icon('heroicon-o-clipboard-document-check'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListStorageCategories::route('/'),
            'create'     => Pages\CreateStorageCategory::route('/create'),
            'view'       => Pages\ViewStorageCategory::route('/{record}'),
            'edit'       => Pages\EditStorageCategory::route('/{record}/edit'),
            'packages'   => Pages\ManageCapacityByPackages::route('/{record}/packages'),
            'products'   => Pages\ManageCapacityByProducts::route('/{record}/products'),
            'locations'  => Pages\ManageLocations::route('/{record}/locations'),
        ];
    }
}
