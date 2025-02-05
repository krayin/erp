<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources;

use Webkul\Sale\Filament\Clusters\ToInvoice;
use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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

    // public static function getGloballySearchableAttributes(): array
    // {
    //     return [
    //         'name',
    //     ];
    // }

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
            'index' => Pages\ListOrderToInvoices::route('/'),
            'create' => Pages\CreateOrderToInvoice::route('/create'),
            'view' => Pages\ViewOrderToInvoice::route('/{record}'),
            'edit' => Pages\EditOrderToInvoice::route('/{record}/edit'),
        ];
    }
}
