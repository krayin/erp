<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources;

use Webkul\Sale\Filament\Clusters\Orders;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;
use Webkul\Sale\Models\Order;
use Filament\Resources\Resource;
use Webkul\Sale\Enums\OrderState;
use Webkul\Sale\Traits\HasQuotationAndOrder;

class QuotationResource extends Resource
{
    use HasQuotationAndOrder;

    protected static ?string $model = Order::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $cluster = Orders::class;

    public static function getModelLabel(): string
    {
        return __('Quotations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Quotations');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'view' => Pages\ViewQuotation::route('/{record}'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
