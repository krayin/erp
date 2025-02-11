<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources;

use Filament\Forms\Form;
use Webkul\Sale\Filament\Clusters\Orders as OrderClusters;
use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Webkul\Sale\Enums\OrderState;
use Webkul\Sale\Models\Order;

class OrdersResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $cluster = OrderClusters::class;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('Orders');
    }

    public static function getNavigationLabel(): string
    {
        return __('Orders');
    }

    public static function form(Form $form): Form
    {
        return QuotationResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return QuotationResource::table($table)
            ->modifyQueryUsing(function ($query) {
                $query->where('state', OrderState::SALE->value);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrders::route('/create'),
            'view' => Pages\ViewOrders::route('/{record}'),
            'edit' => Pages\EditOrders::route('/{record}/edit'),
        ];
    }
}
