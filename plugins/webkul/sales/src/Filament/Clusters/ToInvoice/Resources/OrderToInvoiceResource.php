<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources;

use Webkul\Sale\Filament\Clusters\ToInvoice;
use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;
use Filament\Resources\Resource;
use Webkul\Sale\Enums\InvoiceStatus;
use Webkul\Sale\Models\Order;
use Webkul\Sale\Traits\HasSaleOrders;

class OrderToInvoiceResource extends Resource
{
    use HasSaleOrders;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $cluster = ToInvoice::class;

    public static function getInvoiceStatus(): ?string
    {
        return InvoiceStatus::TO_INVOICE->value;
    }

    public static function getModelLabel(): string
    {
        return __('Orders To Invoice');
    }

    public static function getNavigationLabel(): string
    {
        return __('Orders To Invoice');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrderToInvoices::route('/'),
            'create' => Pages\CreateOrderToInvoice::route('/create'),
            'view'   => Pages\ViewOrderToInvoice::route('/{record}'),
            'edit'   => Pages\EditOrderToInvoice::route('/{record}/edit'),
        ];
    }
}
