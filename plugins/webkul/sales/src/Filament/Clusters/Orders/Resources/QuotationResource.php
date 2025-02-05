<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources;

use Webkul\Sale\Filament\Clusters\Orders;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Partner\Models\Partner;
use Webkul\Sale\Enums\OrderState;
use Webkul\Sale\Models\Order;

class QuotationResource extends Resource
{
    protected static ?string $model = Order::class;

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        ProgressStepper::make('stage_id')
                            ->hiddenLabel()
                            ->inline()
                            ->options(OrderState::class)
                            ->columnSpan('full')
                            ->live()
                            ->reactive(),
                    ])->columns(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->hiddenLabel()
                                    ->schema([
                                        // Forms\Components\RichEditor::make('description')
                                        //     ->label(__('Description'))
                                        //     ->columnSpanFull(),
                                        // Forms\Components\RichEditor::make('requirements')
                                        //     ->label(__('Requirements'))
                                        //     ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Select::make('partner_id')
                                            ->relationship('partner', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->label('Customer'),
                                        Forms\Components\Placeholder::make('partner_address')
                                            ->hiddenLabel()
                                            ->visible(
                                                fn(Get $get) =>
                                                Partner::with('addresses')->find($get('partner_id'))?->addresses->isNotEmpty()
                                            )
                                            ->content(function (Get $get) {
                                                $partner = Partner::with('addresses.state', 'addresses.country')->find($get('partner_id'));

                                                if (
                                                    ! $partner
                                                    || $partner->addresses->isEmpty()
                                                ) {
                                                    return null;
                                                }

                                                $address = $partner->addresses->first();

                                                return sprintf(
                                                    "%s\n%s%s\n%s, %s %s\n%s",
                                                    $address->name ?? '',
                                                    $address->street1 ?? '',
                                                    $address->street2 ? ', ' . $address->street2 : '',
                                                    $address->city ?? '',
                                                    $address->state ? $address->state->name : '',
                                                    $address->zip ?? '',
                                                    $address->country ? $address->country->name : ''
                                                );
                                            }),
                                        Forms\Components\Select::make('payment_term_id')
                                            ->relationship('paymentTerm', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Payment Terms'),
                                    ]),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make('Invoice & Delivery Addresses')
                                            ->schema([
                                                Forms\Components\Select::make('invoice_address_id')
                                                    ->relationship('partnerInvoice', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->label('Invoice Address'),
                                                Forms\Components\Select::make('delivery_address_id')
                                                    ->relationship('partnerShipping', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->label('Delivery Address'),
                                            ])->columns(1)
                                    ]),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make('Expiration & Quotation Date')
                                            ->schema([
                                                Forms\Components\DatePicker::make('validity_date')
                                                    ->live()
                                                    ->native(false)
                                                    ->suffixIcon('heroicon-o-calendar')
                                                    ->default(now()->addDays(30)->format('Y-m-d'))
                                                    ->label('Expiration'),
                                                Forms\Components\DatePicker::make('date_order')
                                                    ->live()
                                                    ->native(false)
                                                    ->suffixIcon('heroicon-o-calendar')
                                                    ->default(now())
                                                    ->label('Quotation Date'),
                                            ])->columns(1),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns('full');
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
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'view' => Pages\ViewQuotation::route('/{record}'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
