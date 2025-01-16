<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Enums;

class MoveResource extends Resource
{
    protected static ?string $model = Move::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.product'))
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                        if ($product = Product::find($get('product_id'))) {
                            $set('product_packaging_id', $product->product_packaging_id);

                            $set('uom_id', $product->uom_id);
                        }
                    }),
                Forms\Components\Select::make('product_packaging_id')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.packaging'))
                    ->relationship('productPackaging', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('product_uom_qty')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.demand'))
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                Forms\Components\Select::make('uom_id')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.unit'))
                    ->relationship(
                        'uom',
                        'name',
                        fn ($query) => $query->where('category_id', 1),
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.product'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('finalLocation.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.final-location'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.description'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.scheduled-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.deadline'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('productPackaging.name')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.packaging'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_uom_qty')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.demand'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.quantity'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_picked')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.picked'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('uom.name')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.unit'))
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
