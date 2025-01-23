<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'quantities';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/products/resources/package/relation-managers/products.table.columns.product')),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('inventories::filament/clusters/products/resources/package/relation-managers/products.table.columns.quantity')),
                Tables\Columns\TextColumn::make('product.uom.name')
                    ->label(__('inventories::filament/clusters/products/resources/package/relation-managers/products.table.columns.unit-of-measure')),
            ])
            ->paginated(false);
    }
}
