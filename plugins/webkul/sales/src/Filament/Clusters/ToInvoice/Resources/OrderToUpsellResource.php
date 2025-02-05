<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources;

use Webkul\Sale\Filament\Clusters\ToInvoice;
use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Sale\Models\Order;

class OrderToUpsellResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $cluster = ToInvoice::class;

    public static function getModelLabel(): string
    {
        return __('Orders To Upsell');
    }

    public static function getNavigationLabel(): string
    {
        return __('Orders To Upsell');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOrderToUpsells::route('/'),
            'create' => Pages\CreateOrderToUpsell::route('/create'),
            'view' => Pages\ViewOrderToUpsell::route('/{record}'),
            'edit' => Pages\EditOrderToUpsell::route('/{record}/edit'),
        ];
    }
}
