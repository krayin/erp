<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource\Pages;

use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource;

class ManageProducts extends ManageRelatedRecords
{
    protected static string $resource = PackageResource::class;

    protected static string $relationship = 'quantities';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/package/pages/manage-products.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/products/resources/package/pages/manage-products.table.columns.product')),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('inventories::filament/clusters/products/resources/package/pages/manage-products.table.columns.quantity')),
                Tables\Columns\TextColumn::make('product.uom.name')
                    ->label(__('inventories::filament/clusters/products/resources/package/pages/manage-products.table.columns.unit-of-measure')),
            ])
            ->paginated(false);
    }
}
