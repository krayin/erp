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
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Tables\Columns\Summarizers\Sum;
use Webkul\Sale\Enums\OrderDisplayType;
use Webkul\Sale\Models\Order;
use Webkul\Sale\Models\OrderSale;
use Webkul\Sale\Models\OrderTemplate;
use Webkul\Sale\Models\Product;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Support\Models\UOM;

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
                        Forms\Components\Hidden::make('currency_id')
                            ->default(Currency::first()->id),
                        ProgressStepper::make('stage_id')
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
                                                Forms\Components\View::make('sales::orders/quotation-totals')
                                                    ->extraAttributes([
                                                        'user' => 'Suraj',
                                                    ]),
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
                                                        ->map(fn($item) => [
                                                            'product_id' => $item->product_id ?? null,
                                                            'name' => $item->name,
                                                            'quantity' => $item->quantity ?? null,
                                                        ])
                                                        ->toArray();

                                                    $set('products', $initialProducts);

                                                    $initialSections = collect($orderTemplate->sections)
                                                        ->map(fn($item) => [
                                                            'product_id' => $item->product_id ?? null,
                                                            'name' => $item->name,
                                                            'quantity' => $item->quantity ?? null,
                                                        ])
                                                        ->toArray();

                                                    $set('sections', $initialSections);

                                                    $initialNotes = collect($orderTemplate->notes)
                                                        ->map(fn($item) => [
                                                            'product_id' => $item->product_id ?? null,
                                                            'name' => $item->name,
                                                            'quantity' => $item->quantity ?? null,
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

    public static function getProductRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('products')
            ->relationship('orderSalesProducts')
            ->hiddenLabel()
            ->live()
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
                        $livewire
                    ): void {
                        $recordId = explode('-', $arguments['item'])[1];

                        // $redirectUrl = OrderTemplateProductResource::getUrl('edit', ['record' => $recordId]);

                        // $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
                    }),
            ])
            ->mutateRelationshipDataBeforeCreateUsing(function ($data) {
                $data['sort'] = OrderSale::max('sort') + 1;

                $data['company_id'] = $data['company_id'] ?? Company::first()->id;

                $data['product_uom_id'] = $data['product_uom_id'] ?? UOM::first()->id;

                $data['creator_id'] = $data['creator_id'] ?? User::first()->id;

                $data['product_uom_qty'] = $data['product_uom_qty'] ?? 1;

                $data['price_unit'] = $data['price_unit'] ?? 0;

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
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $product = Product::find($state);

                                            $set('name', $product->name);

                                            $set('amount', $product->price * ($get('quantity') ?? 1));

                                            $set('unit_price', $product->price);
                                        }
                                    })
                                    ->required(),
                                Forms\Components\Hidden::make('name')
                                    ->live(onBlur: true),
                                Forms\Components\TextInput::make('description')
                                    ->live(onBlur: true)
                                    ->placeholder('Product Description'),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->default(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $product = Product::find($get('product_id'));

                                        $set('amount', $product->price * $state);
                                    })
                                    ->label('Quantity'),
                                Forms\Components\TextInput::make('customer_lead')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->label('Lead Time'),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->label('Unit Price'),
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->live()
                                    ->required()
                                    ->readOnly()
                                    ->label('Amount'),
                            ]),
                    ])->columns(2)
            ]);
    }

    public static function getSectionRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('sections')
            ->relationship('orderSalesSections')
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
                        $livewire
                    ): void {
                        // $recordId = explode('-', $arguments['item'])[1];

                        // $redirectUrl = OrderTemplateProductResource::getUrl('edit', ['record' => $recordId]);

                        // $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
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
            ->relationship('orderSalesNotes')
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
                        $livewire
                    ): void {
                        // $recordId = explode('-', $arguments['item'])[1];

                        // $redirectUrl = OrderTemplateProductResource::getUrl('edit', ['record' => $recordId]);

                        // $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
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
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'view' => Pages\ViewQuotation::route('/{record}'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
