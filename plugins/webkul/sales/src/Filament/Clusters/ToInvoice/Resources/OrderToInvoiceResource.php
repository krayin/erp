<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Webkul\Sale\Filament\Clusters\ToInvoice;
use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Webkul\Sale\Enums\InvoiceStatus;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Webkul\Sale\Models\Order;

class OrderToInvoiceResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $cluster = ToInvoice::class;

    public static function getModelLabel(): string
    {
        return __('Orders To Invoice');
    }

    public static function getNavigationLabel(): string
    {
        return __('Orders To Invoice');
    }

    public static function form(Form $form): Form
    {
        return QuotationResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return QuotationResource::table($table)
            ->modifyQueryUsing(function ($query) {
                $query->where('invoice_status', InvoiceStatus::TO_INVOICE->value);
            });
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return QuotationResource::infolist($infolist);
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
