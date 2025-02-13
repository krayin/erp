<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources;

use Webkul\Sale\Filament\Clusters\Orders;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;
use Webkul\Sale\Models\Order;
use Filament\Resources\Resource;
use Webkul\Sale\Livewire\Summary;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Partner\Models\Partner;
use Webkul\Sale\Enums\OrderState;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Webkul\Account\Enums\TypeTaxUse;
use Webkul\Account\Models\Tax;
use Webkul\Sale\Enums\InvoiceStatus;
use Webkul\Sale\Enums\OrderDisplayType;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource;
use Webkul\Sale\Models\SaleOrderLine;
use Webkul\Sale\Models\OrderTemplate;
use Webkul\Sale\Models\Product;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Support\Models\UOM;

class QuotationResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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
                        Forms\Components\Hidden::make('currency_id')
                            ->default(Currency::first()->id),
                        ProgressStepper::make('state')
                            ->hiddenLabel()
                            ->inline()
                            ->options(OrderState::class)
                            ->default(OrderState::DRAFT->value)
                            ->columnSpan('full')
                            ->disabled()
                            ->live()
                            ->reactive(),
                    ])->columns(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Tabs::make()
                                    ->tabs([
                                        Forms\Components\Tabs\Tab::make(__('Products'))
                                            ->schema([
                                                static::getProductRepeater(),
                                                static::getSectionRepeater(),
                                                static::getNoteRepeater(),
                                                Forms\Components\Livewire::make(Summary::class, function (Get $get) {
                                                    return [
                                                        'products' => $get('products'),
                                                    ];
                                                })
                                                    ->live()
                                                    ->reactive()
                                            ]),
                                        Forms\Components\Tabs\Tab::make(__('Other Info'))
                                            ->schema([
                                                Forms\Components\Fieldset::make('Sales')
                                                    ->schema([
                                                        Forms\Components\Grid::make(2)
                                                            ->schema([
                                                                Forms\Components\Select::make('user_id')
                                                                    ->relationship('user', 'name')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->label('Sales Person'),
                                                                Forms\Components\Select::make('team_id')
                                                                    ->relationship('team', 'name')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->label('Sales Team'),
                                                                Forms\Components\Fieldset::make('Signature & Payment')
                                                                    ->schema([
                                                                        Forms\Components\Toggle::make('require_signature')
                                                                            ->label('Online Signature'),
                                                                        Forms\Components\Toggle::make('require_payment')
                                                                            ->live()
                                                                            ->label('Online Payment'),
                                                                        Forms\Components\TextInput::make('prepayment_percentage')
                                                                            ->prefix('of')
                                                                            ->suffix('%')
                                                                            ->visible(fn(Get $get) => $get('require_payment') === true),
                                                                    ])->columns(1),
                                                                Forms\Components\TextInput::make('client_order_ref')
                                                                    ->label('Customer Reference'),
                                                            ])
                                                    ]),
                                                Forms\Components\Fieldset::make('Invoicing')
                                                    ->schema([
                                                        Forms\Components\Select::make('fiscal_position_id')
                                                            ->relationship('fiscalPosition', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Fiscal Position'),
                                                        Forms\Components\Select::make('journal_id')
                                                            ->relationship('journal', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Invoicing Journal'),
                                                    ]),
                                                Forms\Components\Fieldset::make('Shipping')
                                                    ->schema([
                                                        Forms\Components\DateTimePicker::make('commitment_date')
                                                            ->native(false)
                                                            ->suffixIcon('heroicon-o-calendar')
                                                            ->label('Delivery Date'),
                                                    ]),
                                                Forms\Components\Fieldset::make('Tracking')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('origin')
                                                            ->label('Source Document'),
                                                        Forms\Components\Select::make('medium_id')
                                                            ->relationship('medium', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Medium'),
                                                        Forms\Components\Select::make('source_id')
                                                            ->relationship('utmSource', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Source'),
                                                    ]),
                                            ]),
                                        Forms\Components\Tabs\Tab::make(__('Terms & Conditions'))
                                            ->schema([
                                                Forms\Components\RichEditor::make('note')
                                                    ->hiddenLabel()
                                            ]),
                                    ])
                                    ->persistTabInQueryString(),
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
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                if ($state) {
                                                    if ($get('partner_invoice_id') === null) {
                                                        $set('partner_invoice_id', $state);
                                                    }

                                                    if ($get('partner_shipping_id') === null) {
                                                        $set('partner_shipping_id', $state);
                                                    }
                                                }
                                            })
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
                                        Forms\Components\Select::make('sale_order_template_id')
                                            ->relationship('quotationTemplate', 'name')
                                            ->searchable()
                                            ->live()
                                            ->preload()
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                $orderTemplate = OrderTemplate::find($state);

                                                if ($orderTemplate) {
                                                    $initialProducts = collect($orderTemplate->products)
                                                        ->map(function ($item) {
                                                            $qty = $item->quantity ?? 0;
                                                            $price = $item->product?->price ?? 0;

                                                            return [
                                                                'product_id' => $item->product_id ?? null,
                                                                'name' => $item->name,
                                                                'product_uom_qty' => $qty,
                                                                'tax' => $item->product?->productTaxes->pluck('id')->toArray() ?? [],
                                                                'customer_lead' => 1,
                                                                'price_unit' => $price,
                                                                'price_subtotal' => number_format($price * $qty, 2, '.', ''),
                                                                'price_total' => number_format($price * $qty, 2, '.', '')
                                                            ];
                                                        })
                                                        ->toArray();

                                                    $set('products', $initialProducts);

                                                    $initialSections = collect($orderTemplate->sections)
                                                        ->map(fn($item) => [
                                                            'product_id' => $item->product_id ?? null,
                                                            'name'       => $item->name,
                                                            'quantity'   => $item->quantity ?? null,
                                                        ])
                                                        ->toArray();

                                                    $set('sections', $initialSections);

                                                    $initialNotes = collect($orderTemplate->notes)
                                                        ->map(fn($item) => [
                                                            'product_id' => $item->product_id ?? null,
                                                            'name'       => $item->name,
                                                            'quantity'   => $item->quantity ?? null,
                                                        ])
                                                        ->toArray();

                                                    $set('notes', $initialNotes);
                                                }
                                            })
                                            ->label('Quotation Template'),
                                    ]),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make('Invoice & Delivery Addresses')
                                            ->schema([
                                                Forms\Components\Select::make('partner_invoice_id')
                                                    ->relationship('partnerInvoice', 'name')
                                                    ->searchable()
                                                    ->required()
                                                    ->preload()
                                                    ->live()
                                                    ->label('Invoice Address'),
                                                Forms\Components\Select::make('partner_shipping_id')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label('Status')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => OrderState::options()[$state] ?? $state)
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        OrderState::DRAFT->value => 'gray',
                        OrderState::SENT->value => 'primary',
                        OrderState::SALE->value => 'success',
                        OrderState::CANCEL->value => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_status')
                    ->label('Invoice Status')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => InvoiceStatus::options()[$state] ?? $state)
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creation Date')
                    ->placeholder('-')
                    ->searchable()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commitment_date')
                    ->label('Commitment Date')
                    ->placeholder('-')
                    ->searchable()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_date')
                    ->label('Expected Date')
                    ->placeholder('-')
                    ->searchable()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Customer')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Sales Person')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Sales Team')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_untaxed')
                    ->label('Untaxed Amount')
                    ->placeholder('-')
                    ->searchable()
                    ->summarize(Sum::make()->label('Total'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_tax')
                    ->label('Amount Tax')
                    ->placeholder('-')
                    ->searchable()
                    ->summarize(Sum::make()->label('Taxes'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_total')
                    ->label('Amount Total')
                    ->placeholder('-')
                    ->searchable()
                    ->summarize(Sum::make()->label('Total Amount'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_order_ref')
                    ->label('Customer Reference')
                    ->placeholder('-')
                    ->badge()
                    ->searchable()
                    ->sortable(),
            ])
            ->filtersFormColumns(2)
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('user.name')
                            ->label(__('Sales Person'))
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Sales Person'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('utm_source_id.name')
                            ->label(__('UTM Source'))
                            ->icon('heroicon-o-speaker-wave')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('UTM Source'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company.name')
                            ->label(__('Company'))
                            ->icon('heroicon-o-building-office')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Company'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('partner.name')
                            ->label(__('Customer'))
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Customer'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('journal.name')
                            ->label(__('Journal'))
                            ->icon('heroicon-o-speaker-wave')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('journal'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('partnerInvoice.name')
                            ->label(__('Invoice Address'))
                            ->icon('heroicon-o-map')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Invoice Address'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('partnerShipping.name')
                            ->label(__('Shipping Address'))
                            ->icon('heroicon-o-map')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Shipping Address'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('fiscalPosition.name')
                            ->label(__('Fiscal Position'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Fiscal Position'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                                OrderState::DRAFT->value
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('paymentTerm.name')
                            ->label(__('Payment Term'))
                            ->icon('heroicon-o-currency-dollar')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Payment Term'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('currency.name')
                            ->label(__('Currency'))
                            ->icon('heroicon-o-banknotes')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('UTM Source'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('Created At')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('Updated At')),
                    ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('medium.name')
                    ->label(__('Medium'))
                    ->collapsible(),
                Tables\Grouping\Group::make('utmSource.name')
                    ->label(__('Source'))
                    ->collapsible(),
                Tables\Grouping\Group::make('team.name')
                    ->label(__('Team'))
                    ->collapsible(),
                Tables\Grouping\Group::make('user.name')
                    ->label(__('Sales Person'))
                    ->collapsible(),
                Tables\Grouping\Group::make('currency.full_name')
                    ->label(__('Currency'))
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label(__('Company'))
                    ->collapsible(),
                Tables\Grouping\Group::make('partner.name')
                    ->label(__('Customer'))
                    ->collapsible(),
                Tables\Grouping\Group::make('date_order')
                    ->label(__('Quotation Date'))
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('commitment_date')
                    ->label(__('Commitment Date'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(['default' => 3])
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Tabs::make('Tabs')
                                    ->tabs([
                                        Infolists\Components\Tabs\Tab::make('Products')
                                            ->schema([
                                                Infolists\Components\RepeatableEntry::make('salesOrderLines')
                                                    ->label('Products')
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('product.name')
                                                            ->icon('heroicon-o-shopping-bag')
                                                            ->label('Product'),
                                                        Infolists\Components\TextEntry::make('name')
                                                            ->icon('heroicon-o-document')
                                                            ->label('Description'),
                                                        Infolists\Components\RepeatableEntry::make('product.productTaxes')
                                                            ->contained(false)
                                                            ->hiddenLabel()
                                                            ->schema([
                                                                Infolists\Components\TextEntry::make('name')
                                                                    ->badge()
                                                                    ->tooltip(fn($state) => $state)
                                                                    ->icon('heroicon-o-receipt-percent')
                                                                    ->label('Description'),
                                                            ])
                                                            ->label('Tax'),
                                                        Infolists\Components\TextEntry::make('product_uom_qty')
                                                            ->label('Quantity')
                                                            ->numeric(),
                                                        Infolists\Components\TextEntry::make('price_unit')
                                                            ->label('Unit Price')
                                                            ->icon('heroicon-o-banknotes')
                                                            ->money('USD'),
                                                        Infolists\Components\TextEntry::make('price_subtotal')
                                                            ->label('Subtotal')
                                                            ->icon('heroicon-o-banknotes')
                                                            ->money('USD'),
                                                        Infolists\Components\TextEntry::make('price_total')
                                                            ->label('Total')
                                                            ->icon('heroicon-o-banknotes')
                                                            ->money('USD'),
                                                    ])
                                                    ->columns(6),
                                                Infolists\Components\RepeatableEntry::make('salesOrderSectionLines')
                                                    ->hidden(fn($record) => $record->salesOrderSectionLines->isEmpty())
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('product.name')
                                                            ->label('Product'),
                                                        Infolists\Components\TextEntry::make('name')
                                                            ->label('Description'),
                                                        Infolists\Components\TextEntry::make('quantity')
                                                            ->numeric(),
                                                    ])
                                                    ->columns(3),
                                                Infolists\Components\RepeatableEntry::make('salesOrderNoteLines')
                                                    ->hidden(fn($record) => $record->salesOrderNoteLines->isEmpty())
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('product.name')
                                                            ->label('Product'),
                                                        Infolists\Components\TextEntry::make('name')
                                                            ->label('Description'),
                                                        Infolists\Components\TextEntry::make('quantity')
                                                            ->numeric(),
                                                    ])
                                                    ->columns(3),
                                                Infolists\Components\Livewire::make(Summary::class, function ($record) {
                                                    return [
                                                        'products' => $record->salesOrderLines->map(function ($item) {
                                                            return [
                                                                ...$item->toArray(),
                                                                'tax' => $item->product->productTaxes->pluck('id')->toArray(),
                                                            ];
                                                        })->toArray(),
                                                    ];
                                                }),
                                            ]),
                                        Infolists\Components\Tabs\Tab::make('Other Info')
                                            ->schema([
                                                Infolists\Components\Fieldset::make('Sales')
                                                    ->schema([
                                                        Infolists\Components\Grid::make()
                                                            ->schema([
                                                                Infolists\Components\TextEntry::make('user.name')
                                                                    ->label('Sales Person')
                                                                    ->placeholder('—')
                                                                    ->icon('heroicon-o-user'),
                                                                Infolists\Components\TextEntry::make('team.name')
                                                                    ->label('Sales Team')
                                                                    ->placeholder('—')
                                                                    ->icon('heroicon-o-users'),
                                                                Infolists\Components\Fieldset::make('Signature & Payment')
                                                                    ->schema([
                                                                        Infolists\Components\IconEntry::make('require_signature')
                                                                            ->boolean()
                                                                            ->label('Online Signature')
                                                                            ->placeholder('—'),
                                                                        Infolists\Components\IconEntry::make('require_payment')
                                                                            ->boolean()
                                                                            ->label('Online Payment')
                                                                            ->placeholder('—'),
                                                                        Infolists\Components\IconEntry::make('prepayment_percentage')
                                                                            ->boolean()
                                                                            ->label('Online Payment')
                                                                            ->placeholder('—'),
                                                                    ]),
                                                                Infolists\Components\TextEntry::make('client_order_ref')
                                                                    ->label('Customer Reference')
                                                                    ->placeholder('—')
                                                                    ->icon('heroicon-o-document'),
                                                            ])->columns(2),
                                                    ]),
                                                Infolists\Components\Fieldset::make('Invoicing')
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('fiscalPosition.name')
                                                            ->label('Fiscal Position')
                                                            ->placeholder('—')
                                                            ->icon('heroicon-o-receipt-percent'),
                                                        Infolists\Components\TextEntry::make('journal.name')
                                                            ->label('Invoicing Journal')
                                                            ->placeholder('—')
                                                            ->icon('heroicon-o-book-open'),
                                                    ]),
                                                Infolists\Components\Fieldset::make('Shipping')
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('commitment_date')
                                                            ->label('Delivery date')
                                                            ->placeholder('—')
                                                            ->icon('heroicon-o-calendar'),
                                                    ]),
                                                Infolists\Components\Fieldset::make('Tracking')
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('origin')
                                                            ->label('Origin')
                                                            ->placeholder('—')
                                                            ->icon('heroicon-o-globe-alt'),
                                                        Infolists\Components\TextEntry::make('medium.name')
                                                            ->label('Medium')
                                                            ->placeholder('—'),
                                                        Infolists\Components\TextEntry::make('source.name')
                                                            ->label('Source')
                                                            ->placeholder('—'),
                                                    ]),
                                            ]),
                                        Infolists\Components\Tabs\Tab::make('Terms & Conditions')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('note')
                                                    ->markdown()
                                                    ->columnSpanFull()
                                                    ->icon('heroicon-o-information-circle'),
                                            ]),
                                    ])->persistTabInQueryString(),
                            ])->columnSpan(2),
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->label('Number')
                                            ->placeholder('—')
                                            ->size(TextEntrySize::Large)
                                            ->icon('heroicon-o-identification'),
                                        Infolists\Components\TextEntry::make('partner.name')
                                            ->label('Customer')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-user-circle'),
                                        Infolists\Components\TextEntry::make('partner_address')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-map'),
                                        Infolists\Components\TextEntry::make('paymentTerm.name')
                                            ->label('Payment Terms')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-credit-card'),
                                        Infolists\Components\TextEntry::make('quotationTemplate.name')
                                            ->label('Quotation Template')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-document-duplicate'),
                                    ]),
                                Infolists\Components\Section::make()
                                    ->schema([
                                        Infolists\Components\Fieldset::make('Invoice & Delivery Addresses')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('partnerInvoice.name')
                                                    ->label('Invoice Address')
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-home'),
                                                Infolists\Components\TextEntry::make('partnerShipping.name')
                                                    ->label('Delivery Address')
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-truck'),
                                            ]),
                                    ]),
                                Infolists\Components\Section::make()
                                    ->schema([
                                        Infolists\Components\Fieldset::make('Invoice & Delivery Addresses')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('validity_date')
                                                    ->label('Expiration')
                                                    ->date()
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-clock'),
                                                Infolists\Components\TextEntry::make('date_order')
                                                    ->label('Quotation Date')
                                                    ->date()
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-calendar'),
                                            ]),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ]),
            ]);
    }

    public static function getProductRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('products')
            ->relationship('salesOrderLines')
            ->hiddenLabel()
            ->live()
            ->reactive()
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->cloneable()
            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ->deleteAction(
                fn(Action $action) => $action->requiresConfirmation(),
            )
            ->extraItemActions([
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->action(function (array $arguments, $livewire, $state): void {
                        $redirectUrl = ProductResource::getUrl('edit', ['record' => $state[$arguments['item']]['product_id']]);

                        $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
                    }),
            ])
            ->mutateRelationshipDataBeforeCreateUsing(function ($data) {
                $data['sort'] = SaleOrderLine::max('sort') + 1;
                $data['company_id'] = $data['company_id'] ?? Company::first()->id;
                $data['product_uom_id'] = $data['product_uom_id'] ?? UOM::first()->id;
                $data['creator_id'] = $data['creator_id'] ?? User::first()->id;
                $data['customer_lead'] = $data['customer_lead'] ?? 0;

                return $data;
            })
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->label('Product')
                                    ->afterStateHydrated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            $quantity = floatval($get('product_uom_qty') ?? 1);
                                            $priceUnit = floatval($product->price);

                                            $set('name', $product->name);
                                            $set('price_unit', $priceUnit);

                                            $subtotal = $quantity * $priceUnit;
                                            $set('price_subtotal', number_format($subtotal, 2, '.', ''));
                                            $set('price_total', number_format($subtotal, 2, '.', ''));

                                            $set('tax', $product->productTaxes->pluck('id')->toArray());
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            $quantity = floatval($get('product_uom_qty') ?? 1);
                                            $priceUnit = floatval($product->price);

                                            $set('name', $product->name);
                                            $set('price_unit', $priceUnit);

                                            $subtotal = $quantity * $priceUnit;
                                            $set('price_subtotal', number_format($subtotal, 2, '.', ''));
                                            $set('price_total', number_format($subtotal, 2, '.', ''));

                                            $set('tax', $product->productTaxes->pluck('id')->toArray());
                                        }
                                    })
                                    ->required(),
                                Forms\Components\Hidden::make('name')
                                    ->live(onBlur: true),
                                Forms\Components\TextInput::make('product_uom_qty')
                                    ->required()
                                    ->default(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($get('product_id')) {
                                            $product = Product::find($get('product_id'));
                                            $quantity = floatval($state);
                                            $priceUnit = floatval($get('price_unit') ?? $product->price);

                                            $subtotal = $quantity * $priceUnit;
                                            $set('price_subtotal', number_format($subtotal, 2, '.', ''));
                                            $set('price_total', number_format($subtotal, 2, '.', ''));
                                        }
                                    })
                                    ->label('Quantity'),
                                Forms\Components\Select::make('tax')
                                    ->options(Tax::where('type_tax_use', TypeTaxUse::SALE->value)->pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->label('Taxes')
                                    ->multiple()
                                    ->preload()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        if ($get('product_id')) {
                                            $product = Product::find($get('product_id'));

                                            $product->productTaxes()->sync($state);
                                        }
                                    })
                                    ->live(),
                                Forms\Components\TextInput::make('customer_lead')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->label('Lead Time'),
                                Forms\Components\TextInput::make('price_unit')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($get('product_id')) {
                                            $quantity = floatval($get('product_uom_qty') ?? 1);
                                            $priceUnit = floatval($state);

                                            $subtotal = $quantity * $priceUnit;

                                            $taxIds = $get('tax') ?? [];
                                            $taxAmount = 0;

                                            if (!empty($taxIds)) {
                                                $taxes = \Webkul\Account\Models\Tax::whereIn('id', $taxIds)->get();
                                                foreach ($taxes as $tax) {
                                                    $taxValue = floatval($tax->amount);
                                                    if ($tax->include_base_amount) {
                                                        $subtotal = $subtotal / (1 + ($taxValue / 100));
                                                    } else {
                                                        $taxAmount += $subtotal * ($taxValue / 100);
                                                    }
                                                }
                                            }

                                            $set('price_subtotal', number_format($subtotal, 2, '.', ''));
                                            $set('price_total', number_format($subtotal + $taxAmount, 2, '.', ''));
                                        }
                                    })
                                    ->label('Unit Price'),
                                Forms\Components\TextInput::make('price_subtotal')
                                    ->numeric()
                                    ->live()
                                    ->required()
                                    ->readOnly()
                                    ->label('Subtotal'),
                                Forms\Components\TextInput::make('price_total')
                                    ->numeric()
                                    ->live()
                                    ->required()
                                    ->readOnly()
                                    ->label('Total'),
                            ]),
                    ])->columns(2)
            ]);
    }

    public static function getSectionRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('sections')
            ->relationship('salesOrderSectionLines')
            ->hiddenLabel()
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->cloneable()
            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ->deleteAction(
                fn(Action $action) => $action->requiresConfirmation(),
            )
            ->extraItemActions([
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->action(function (
                        array $arguments,
                        $livewire,
                        $state,
                    ): void {
                        $redirectUrl = ProductResource::getUrl('edit', ['record' => $state[$arguments['item']]['product_id']]);

                        $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
                    }),
            ])
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->live(onBlur: true)
                            ->label('Name'),
                        Forms\Components\Hidden::make('quantity')
                            ->required()
                            ->default(0),
                        Forms\Components\Hidden::make('display_type')
                            ->required()
                            ->default(OrderDisplayType::SECTION->value)
                    ]),
            ]);
    }

    public static function getNoteRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('notes')
            ->relationship('salesOrderNoteLines')
            ->hiddenLabel()
            ->reorderable()
            ->defaultItems(0)
            ->collapsible()
            ->cloneable()
            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ->deleteAction(
                fn(Action $action) => $action->requiresConfirmation(),
            )
            ->extraItemActions([
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->action(function (
                        array $arguments,
                        $livewire,
                        $state,
                    ): void {
                        $redirectUrl = ProductResource::getUrl('edit', ['record' => $state[$arguments['item']]['product_id']]);

                        $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
                    }),
            ])
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->live(onBlur: true)
                            ->label('Name'),
                        Forms\Components\Hidden::make('quantity')
                            ->required()
                            ->default(0),
                        Forms\Components\Hidden::make('display_type')
                            ->required()
                            ->default(OrderDisplayType::NOTE->value)
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'view'   => Pages\ViewQuotation::route('/{record}'),
            'edit'   => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
