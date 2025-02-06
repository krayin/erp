<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources;

use Webkul\Sale\Filament\Clusters\Orders as OrderClusters;
use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;
use Filament\Resources\Resource;
use Webkul\Sale\Enums\OrderState;
use Webkul\Sale\Models\Order;
use Webkul\Sale\Traits\HasQuotationAndOrder;

class OrdersResource extends Resource
{
    use HasQuotationAndOrder;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $cluster = OrderClusters::class;

    public static function getDefaultState(): ?string
    {
        return OrderState::SALE->value;
    }

    public static function getModelLabel(): string
    {
        return __('Orders');
    }

    public static function getNavigationLabel(): string
    {
        return __('Orders');
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
