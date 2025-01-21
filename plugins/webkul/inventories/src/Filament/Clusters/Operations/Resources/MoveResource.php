<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Settings\ProductSettings;
use Webkul\Inventory\Settings\WarehouseSettings;

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
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                        if ($product = Product::find($get('product_id'))) {
                            $set('product_packaging_id', $product->product_packaging_id);

                            $set('uom_id', $product->uom_id);
                        }
                    }),
                Forms\Components\Select::make('final_location_id')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.final-location'))
                    ->relationship('finalLocation', 'full_name')
                    ->searchable()
                    ->preload()
                    ->visible(fn (WarehouseSettings $warehouseSettings) => $warehouseSettings->enable_locations),
                Forms\Components\TextInput::make('description')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.description')),
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.scheduled-at'))
                    ->default(now())
                    ->native(false),
                Forms\Components\DateTimePicker::make('deadline')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.deadline'))
                    ->native(false),
                Forms\Components\Select::make('product_packaging_id')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.packaging'))
                    ->relationship('productPackaging', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn (ProductSettings $productSettings) => $productSettings->enable_packagings),
                Forms\Components\TextInput::make('requested_qty')
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
                    ->required()
                    ->visible(fn (ProductSettings $productSettings) => $productSettings->enable_uom),
                Forms\Components\Toggle::make('is_picked')
                    ->label(__('inventories::filament/clusters/operations/resources/move.form.fields.picked'))
                    ->default(0),
            ]);
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
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (WarehouseSettings $warehouseSettings) => $warehouseSettings->enable_locations),
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
                    ->sortable()
                    ->visible(fn (ProductSettings $productSettings) => $productSettings->enable_packagings),
                Tables\Columns\TextColumn::make('requested_qty')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.demand'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('qty')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.quantity'))
                    ->searchable()
                    ->sortable()
                    ->disabled(fn (Move $record): bool => $record->state === Enums\MoveState::DRAFT)
                    ->beforeStateUpdated(function (Move $record, $state) {
                        if (in_array($record->operation->state, [Enums\OperationState::DRAFT, Enums\OperationState::WAITING])) {
                            Notification::make()
                                ->success()
                                ->title(__('projects::filament/resources/task.table.columns.qty.notification.title'))
                                ->body(__('projects::filament/resources/task.table.columns.qty.notification.body'))
                                ->warning()
                                ->send();

                            throw \Illuminate\Validation\ValidationException::withMessages([]);
                        }
                    })
                    ->afterStateUpdated(function (Move $record, $state) {
                        Notification::make()
                            ->success()
                            ->title(__('projects::filament/resources/task.table.actions.delete.notification.title'))
                            ->body(__('projects::filament/resources/task.table.actions.delete.notification.body'))
                            ->success()
                            ->send();
                    }),
                Tables\Columns\TextColumn::make('is_picked')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.picked'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('uom.name')
                    ->label(__('inventories::filament/clusters/operations/resources/move.table.columns.unit'))
                    ->searchable()
                    ->sortable()
                    ->visible(fn (ProductSettings $productSettings) => $productSettings->enable_uom),
            ]);
    }
}
