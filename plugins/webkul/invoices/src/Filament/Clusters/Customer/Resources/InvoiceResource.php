<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources;

use Webkul\Invoice\Filament\Clusters\Customer;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Account\Models\Move as AccountMove;
use Webkul\Sale\Livewire\Summary;
use Filament\Forms\Get;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Partner\Models\Partner;
use Filament\Forms\Set;
use Webkul\Support\Models\Currency;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Auth;
use Webkul\Invoice\Enums\AutoPost;
use Webkul\Invoice\Enums\MoveState;
use Webkul\Account\Enums\TypeTaxUse;
use Webkul\Account\Models\MoveLine;
use Webkul\Account\Models\Tax;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\TeamResource;
use Webkul\Sale\Models\Product;
use Webkul\Security\Filament\Resources\UserResource;
class InvoiceResource extends Resource
{
use Webkul\Sale\Models\SaleOrderLine;
        return __('Invoices');
    }

    public static function getNavigationLabel(): string
    {
        return __('Invoices');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Invoices');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'invoice_partner_display_name',
            'invoice_date',
            'invoice_date_due',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Number')           => $record?->name ?? '—',
            __('Customer')         => $record?->invoice_partner_display_name ?? '—',
            __('Invoice Date')     => $record?->invoice_date ?? '—',
            __('Invoice Date Due') => $record?->invoice_date_due ?? '—',
        ];
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
                            ->options(MoveState::class)
                            ->default(MoveState::DRAFT->value)
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
                                                Forms\Components\Fieldset::make('Invoice')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('reference')
                                                            ->label('Customer Reference'),
                                                        Forms\Components\Select::make('invoice_user_id')
                                                            ->relationship('invoiceUser', 'name')
                                                            ->searchable()
                                                            ->createOptionForm(fn(Form $form) => UserResource::form($form))
                                                            ->preload()
                                                            ->label('Sales Person'),
                                                        Forms\Components\Select::make('team_id')
                                                            ->relationship('team', 'name')
                                                            ->createOptionForm(fn(Form $form) => TeamResource::form($form))
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Sales Team'),
                                                        Forms\Components\Select::make('partner_bank_id')
                                                            ->relationship('partnerBank', 'account_holder_name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Recipient Bank'),
                                                        Forms\Components\TextInput::make('payment_reference')
                                                            ->label('Payment Reference'),
                                                        Forms\Components\DatePicker::make('delivery_date')
                                                            ->native(false)
                                                            ->default(now())
                                                            ->label('Delivery Date'),
                                                    ]),
                                                Forms\Components\Fieldset::make('Accounting')
                                                    ->schema([
                                                        Forms\Components\Select::make('invoice_incoterm_id')
                                                            ->relationship('invoiceIncoterm', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Incoterm'),
                                                        Forms\Components\TextInput::make('incoterm_location')
                                                            ->label('Incoterm Location'),
                                                        Forms\Components\Select::make('fiscal_position_id')
                                                            ->relationship('fiscalPosition', 'name')
                                                            ->preload()
                                                            ->searchable()
                                                            ->label('Fiscal Position'),
                                                        Forms\Components\Select::make('preferred_payment_method_line_id')
                                                            ->relationship('paymentMethodLine', 'name')
                                                            ->preload()
                                                            ->searchable()
                                                            ->label('Payment Method'),
                                                        Forms\Components\Select::make('auto_post')
                                                            ->options(AutoPost::class)
                                                            ->default(AutoPost::NO->value)
                                                            ->label('Auto Post'),
                                                        Forms\Components\Toggle::make('checked')
                                                            ->default(false)
                                                            ->inline(false)
                                                            ->label('Checked'),
                                                    ]),
                                            ]),
                                        Forms\Components\Tabs\Tab::make(__('Terms & Conditions'))
                                            ->schema([
                                                Forms\Components\RichEditor::make('narration')
                                                    ->hiddenLabel()
                                                    ->placeholder('Terms & Conditions')
                                            ]),
                                    ])
                                    ->persistTabInQueryString(),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make('General')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->placeholder('INV/2025/1')
                                                    ->label('Customer Invoice'),
                                                Forms\Components\Select::make('partner_id')
                                                    ->relationship(
                                                        'partner',
                                                        'name',
                                                        fn($query) => $query->where('sub_type', 'company'),
                                                    )
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->required()
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

                                            ])->columns(1),
                                    ]),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make('Invoice Date & Payment Term')
                                            ->schema([
                                                Forms\Components\DatePicker::make('invoice_date')
                                                    ->required()
                                                    ->default(now())
                                                    ->native(false)
                                                    ->label('Invoice Date'),
                                                Forms\Components\DatePicker::make('invoice_date_due')
                                                    ->required()
                                                    ->default(now())
                                                    ->native(false)
                                                    ->live()
                                                    ->hidden(fn(Get $get) => $get('invoice_payment_term_id') !== null)
                                                    ->label('Due Date'),
                                                Forms\Components\Select::make('invoice_payment_term_id')
                                                    ->relationship('invoicePaymentTerm', 'name')
                                                    ->required(fn(Get $get) => $get('invoice_date_due') === null)
                                                    ->live()
                                                    ->searchable()
                                                    ->preload()
                                                    ->label('Payment Terms'),
                                            ])->columns(1),
                                    ]),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make('Marketing')
                                            ->schema([
                                                Forms\Components\Select::make('campaign_id')
                                                    ->relationship('campaign', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->label('Campaign'),
                                                Forms\Components\Select::make('medium_id')
                                                    ->relationship('medium', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->label('Medium'),
                                                Forms\Components\Select::make('source_id')
                                                    ->relationship('source', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->label('Source'),
                                            ])->columns(1)
                                    ])
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getProductRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('products')
            ->relationship(
                'moveLines',
                fn($query) => $query->where('display_type', 'product'),
            )
            ->hiddenLabel()
            ->live()
            ->reactive()
            ->reorderable()
            ->addActionLabel('Add a line')
            ->collapsible()
            ->defaultItems(0)
            ->cloneable()
            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ->deleteAction(fn(Action $action) => $action->requiresConfirmation())
            ->extraItemActions([
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->action(function (array $arguments, $livewire, $state): void {
                        $redirectUrl = ProductResource::getUrl('edit', ['record' => $state[$arguments['item']]['product_id']]);
                        $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
                    }),
            ])
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\Hidden::make('currency_id')
                                    ->default(Currency::first()->id),
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->label('Product')
                                    ->afterStateHydrated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            self::updateProductCalculations($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            self::updateProductCalculations($state, $set, $get);
                                        }
                                    })
                                    ->required(),
                                Forms\Components\Hidden::make('name')
                                    ->live(onBlur: true),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->default(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($get('product_id')) {
                                            self::updateLineCalculations($set, $get);
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
                                            self::updateLineCalculations($set, $get);
                                        }
                                    })
                                    ->live(),
                                Forms\Components\TextInput::make('discount')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($get('product_id')) {
                                            self::updateLineCalculations($set, $get);
                                        }
                                    })
                                    ->label('Discount (%)'),
                                Forms\Components\TextInput::make('price_unit')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($get('product_id')) {
                                            self::updateLineCalculations($set, $get);
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
            ])
            ->saveRelationshipsUsing(function (Model $record, $state): void {
                $record->moveLines()->delete();

                foreach ($state as $data) {
                    MoveLine::createOrUpdateProductLine([
                        'move_id'     => $record->id,
                        'company_id'  => $record->company_id,
                        'product_id'  => $data['product_id'],
                        'currency_id' => $data['currency_id'],
                        'name'        => $data['name'],
                        'quantity'    => $data['quantity'],
                        'price_unit'  => $data['price_unit'],
                        'discount'    => $data['discount'],
                        'tax'         => $data['tax'],
                        'created_by'  => Auth::id(),
                    ]);
                }
            });
    }

    private static function updateProductCalculations($productId, Set $set, Get $get): void
    {
        $product = Product::find($productId);
        $quantity = floatval($get('quantity') ?? 1);
        $priceUnit = floatval($product->price);

        $set('name', $product->name);
        $set('price_unit', $priceUnit);
        $set('tax', $product->productTaxes->pluck('id')->toArray());

        self::calculateTotals($quantity, $priceUnit, floatval($get('discount')), $product->productTaxes->pluck('id')->toArray(), $set);
    }

    private static function updateLineCalculations(Set $set, Get $get): void
    {
        $quantity = floatval($get('quantity') ?? 1);
        $priceUnit = floatval($get('price_unit') ?? 0);
        $discount = floatval($get('discount') ?? 0);
        $taxIds = $get('tax') ?? [];

        self::calculateTotals($quantity, $priceUnit, $discount, $taxIds, $set);
    }

    private static function calculateTotals(float $quantity, float $priceUnit, float $discount, array $taxIds, Set $set): void
    {
        $baseAmount = $quantity * $priceUnit;

        $discountAmount = $baseAmount * ($discount / 100);
        $subtotalBeforeTax = $baseAmount - $discountAmount;

        $taxAmount = 0;
        $includedTaxAmount = 0;

        if (! empty($taxIds)) {
            $taxes = Tax::whereIn('id', $taxIds)->get();

            foreach ($taxes as $tax) {
                $taxValue = floatval($tax->amount);
                if ($tax->include_base_amount) {
                    $includedTaxRate = $taxValue / 100;
                    $includedTaxAmount += $subtotalBeforeTax - ($subtotalBeforeTax / (1 + $includedTaxRate));
                }
            }

            $subtotalExcludingIncludedTax = $subtotalBeforeTax - $includedTaxAmount;

            foreach ($taxes as $tax) {
                $taxValue = floatval($tax->amount);
                if (!$tax->include_base_amount) {
                    $taxAmount += $subtotalExcludingIncludedTax * ($taxValue / 100);
                }
            }
        }

        $set('price_subtotal', number_format($subtotalBeforeTax, 2, '.', ''));
        $set('price_total', number_format($subtotalBeforeTax + $taxAmount, 2, '.', ''));
    }
}
