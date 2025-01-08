<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\Pages;
use Webkul\Warehouse\Enums\AllowNewProduct;
use Webkul\Warehouse\Models\StorageCategory;

class StorageCategoryResource extends Resource
{
    protected static ?string $model = StorageCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 4;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'full_name';

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
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.title'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.title'))
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStorageCategories::route('/'),
            'view'  => Pages\ViewStorageCategory::route('/{record}'),
            'edit'  => Pages\EditStorageCategory::route('/{record}/edit'),
        ];
    }
}
