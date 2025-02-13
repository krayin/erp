<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources;

use Webkul\Sale\Filament\Clusters\Products;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource\Pages;
use Webkul\Sale\Models\Product;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Illuminate\Support\Facades\Auth;
use Webkul\Account\Enums\TypeTaxUse;
use Webkul\Account\Models\Tax;
use Webkul\Product\Enums\ProductType;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Sale\Enums\InvoicePolicy;
use Webkul\Support\Models\UOM;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages\ManageProducts;
use Webkul\Sale\Models\ProductCategory;

class ProductResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $cluster = Products::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/products/resources/product.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('is_favorite')
                                        ->hiddenLabel()
                                        ->outlined(false)
                                        ->icon(fn($record) => $record?->is_favorite >= 1 ? 'heroicon-s-star' : 'heroicon-o-star')
                                        ->color('warning')
                                        ->iconButton()
                                        ->size(ActionSize::Large->value)
                                        ->action(fn($record) => $record?->update(['is_favorite' => ! $record->is_favorite,])),
                                ]),
                                Forms\Components\TextInput::make('name')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.general.fields.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->placeholder(__('sales::filament/clusters/products/resources/product.form.sections.general.fields.name-placeholder'))
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                                Forms\Components\Toggle::make('sales_ok')
                                    ->live()
                                    ->default(true)
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.general.fields.sales')),
                                Forms\Components\Toggle::make('purchase_ok')
                                    ->default(true)
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.general.fields.purchase')),
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.general.fields.description')),
                                Forms\Components\Select::make('tags')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.general.fields.tags'))
                                    ->relationship(name: 'tags', titleAttribute: 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('sales::filament/clusters/products/resources/product.form.sections.general.fields.name'))
                                            ->required()
                                            ->unique('products_tags'),
                                    ]),
                            ]),
                        Forms\Components\Section::make()
                            ->visible(fn(Get $get) => $get('sales_ok'))
                            ->schema([
                                Forms\Components\Select::make('invoice_policy')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.invoice-policy.title'))
                                    ->options(InvoicePolicy::class)
                                    ->live()
                                    ->default(InvoicePolicy::ORDER->value),
                                Forms\Components\Placeholder::make('invoice_policy_help')
                                    ->hiddenLabel()
                                    ->content(function (Get $get) {
                                        if ($get('invoice_policy') === InvoicePolicy::ORDER->value) {
                                            return __('sales::filament/clusters/products/resources/product.form.sections.invoice-policy.ordered-policy');
                                        } else if ($get('invoice_policy') === InvoicePolicy::DELIVERY->value) {
                                            return __('sales::filament/clusters/products/resources/product.form.sections.invoice-policy.delivered-policy');
                                        }
                                    }),
                            ]),
                        Forms\Components\Section::make(__('sales::filament/clusters/products/resources/product.form.sections.images.title'))
                            ->schema([
                                Forms\Components\FileUpload::make('images')
                                    ->multiple()
                                    ->storeFileNamesIn('products'),
                            ]),

                        Forms\Components\Section::make(__('sales::filament/clusters/products/resources/product.form.sections.additional.title'))
                            ->visible(! empty($customFormFields = static::getCustomFormFields()))
                            ->schema($customFormFields),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('sales::filament/clusters/products/resources/product.form.sections.settings.title'))
                            ->schema([
                                Forms\Components\Radio::make('type')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.settings.fields.type'))
                                    ->options(ProductType::class)
                                    ->default(ProductType::GOODS->value)
                                    ->live(),
                                Forms\Components\TextInput::make('reference')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.settings.fields.reference'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('barcode')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.settings.fields.barcode'))
                                    ->maxLength(255),

                                Forms\Components\Select::make('company_id')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.settings.fields.company'))
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::user()->default_company_id),
                            ]),
                        Forms\Components\Section::make(__('sales::filament/clusters/products/resources/product.form.sections.category-and-tags.title'))
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.settings.fields.category'))
                                    ->required()
                                    ->relationship('category', 'full_name')
                                    ->searchable()
                                    ->preload()
                                    ->default(ProductCategory::first()?->id)
                                    ->hiddenOn(ManageProducts::class),
                            ]),
                        Forms\Components\Section::make(__('sales::filament/clusters/products/resources/product.form.sections.pricing.title'))
                            ->schema([
                                Forms\Components\Select::make('accounts_product_taxes')
                                    ->relationship(
                                        'productTaxes',
                                        'name',
                                        fn($query) => $query->where('type_tax_use', TypeTaxUse::SALE->value),
                                    )
                                    ->multiple()
                                    ->live()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Placeholder::make('total_tax_inclusion')
                                    ->hiddenLabel()
                                    ->content(function (Get $get) {
                                        $price = floatval($get('price'));
                                        $selectedTaxIds = $get('accounts_product_taxes');

                                        if (!$price || empty($selectedTaxIds)) {
                                            return '';
                                        }

                                        $taxes = Tax::whereIn('id', $selectedTaxIds)->get();

                                        $result = [
                                            'total_excluded' => $price,
                                            'total_included' => $price,
                                            'taxes' => []
                                        ];

                                        $totalTaxAmount = 0;
                                        $basePrice = $price;

                                        foreach ($taxes as $tax) {
                                            $taxAmount = $basePrice * ($tax->amount / 100);
                                            $totalTaxAmount += $taxAmount;

                                            if ($tax->include_base_amount) {
                                                $basePrice += $taxAmount;
                                            }

                                            $result['taxes'][] = [
                                                'tax' => $tax,
                                                'base' => $price,
                                                'amount' => $taxAmount
                                            ];
                                        }

                                        $result['total_excluded'] = $price;
                                        $result['total_included'] = $price + $totalTaxAmount;

                                        $parts = [];

                                        if ($result['total_included'] != $price) {
                                            $parts[] = sprintf(
                                                '%s Incl. Taxes',
                                                number_format($result['total_included'], 2)
                                            );
                                        }

                                        if ($result['total_excluded'] != $price) {
                                            $parts[] = sprintf(
                                                '%s Excl. Taxes',
                                                number_format($result['total_excluded'], 2)
                                            );
                                        }

                                        return !empty($parts) ? '(= ' . implode(', ', $parts) . ')' : ' ';
                                    }),
                                Forms\Components\Select::make('accounts_product_supplier_taxes')
                                    ->relationship(
                                        'supplierTaxes',
                                        'name',
                                        fn($query) => $query->where('type_tax_use', TypeTaxUse::PURCHASE->value),
                                    )
                                    ->multiple()
                                    ->live()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('price')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.pricing.fields.price'))
                                    ->numeric()
                                    ->required()
                                    ->live()
                                    ->default(0.00),
                                Forms\Components\TextInput::make('cost')
                                    ->label(__('sales::filament/clusters/products/resources/product.form.sections.pricing.fields.cost'))
                                    ->numeric()
                                    ->default(0.00),
                                Forms\Components\Hidden::make('uom_id')
                                    ->default(UOM::first()->id),
                                Forms\Components\Hidden::make('uom_po_id')
                                    ->default(UOM::first()->id),
                                Forms\Components\Hidden::make('sale_line_warn')
                                    ->default('no-message'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_favorite')
                    ->label('')
                    ->icon(fn(Product $record): string => $record->is_favorite ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn(Product $record): string => $record->is_favorite ? 'warning' : 'gray')
                    ->action(function (Product $record): void {
                        $record->update([
                            'is_favorite' => ! $record->is_favorite,
                        ]);
                    }),
                Tables\Columns\ImageColumn::make('images')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.images'))
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(isSeparate: true),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.tags'))
                    ->badge()
                    ->state(function (Product $record): array {
                        return $record->tags->map(fn($tag) => [
                            'label' => $tag->name,
                            'color' => $tag->color ?? 'primary',
                        ])->toArray();
                    })
                    ->formatStateUsing(fn($state) => $state['label'])
                    ->color(fn($state) => Color::hex($state['color']))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.barcode'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.company'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.price'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.cost'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.category'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.type'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.deleted-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('sales::filament/clusters/products/resources/product.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('type')
                    ->label(__('sales::filament/clusters/products/resources/product.table.groups.type')),
                Tables\Grouping\Group::make('category.name')
                    ->label(__('sales::filament/clusters/products/resources/product.table.groups.category')),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('sales::filament/clusters/products/resources/product.table.groups.created-at'))
                    ->date(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort', 'desc')
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints(collect(static::mergeCustomTableQueryBuilderConstraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.name')),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('reference')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.reference'))
                            ->icon('heroicon-o-link'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('barcode')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.barcode'))
                            ->icon('heroicon-o-bars-4'),
                        Tables\Filters\QueryBuilder\Constraints\BooleanConstraint::make('is_favorite')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.is-favorite'))
                            ->icon('heroicon-o-star'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('price')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.price'))
                            ->icon('heroicon-o-banknotes'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('cost')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.cost'))
                            ->icon('heroicon-o-banknotes'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('weight')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.weight'))
                            ->icon('heroicon-o-scale'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('volume')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.volume'))
                            ->icon('heroicon-o-beaker'),
                        Tables\Filters\QueryBuilder\Constraints\SelectConstraint::make('type')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.type'))
                            ->multiple()
                            ->options(ProductType::class)
                            ->icon('heroicon-o-queue-list'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('tags')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.tags'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-tag'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.updated-at')),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.company'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-building-office'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('creator')
                            ->label(__('sales::filament/clusters/products/resources/product.table.filters.creator'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                    ]))->filter()->values()->all()),
            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn(Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('sales::filament/clusters/products/resources/product.table.actions.restore.notification.title'))
                                ->body(__('sales::filament/clusters/products/resources/product.table.actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('sales::filament/clusters/products/resources/product.table.actions.delete.notification.title'))
                                ->body(__('sales::filament/clusters/products/resources/product.table.actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('sales::filament/clusters/products/resources/product.table.actions.force-delete.notification.title'))
                                ->body(__('sales::filament/clusters/products/resources/product.table.actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('sales::filament/clusters/products/resources/product.table.bulk-actions.restore.notification.title'))
                                ->body(__('sales::filament/clusters/products/resources/product.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('sales::filament/clusters/products/resources/product.table.bulk-actions.delete.notification.title'))
                                ->body(__('sales::filament/clusters/products/resources/product.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('sales::filament/clusters/products/resources/product.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('sales::filament/clusters/products/resources/product.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.general.entries.name')),

                                Infolists\Components\TextEntry::make('description')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.general.entries.description'))
                                    ->html()
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('tags.name')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.general.entries.tags'))
                                    ->badge()
                                    ->separator(', ')
                                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                            ]),

                        Infolists\Components\Section::make(__('sales::filament/clusters/products/resources/product.infolist.sections.images.title'))
                            ->schema([
                                Infolists\Components\ImageEntry::make('images')
                                    ->hiddenLabel()
                                    ->circular(),
                            ])
                            ->visible(fn($record): bool => ! empty($record->images)),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('sales::filament/clusters/products/resources/product.infolist.sections.record-information.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-o-user'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.record-information.entries.updated-at'))
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar'),
                            ]),

                        Infolists\Components\Section::make(__('sales::filament/clusters/products/resources/product.infolist.sections.settings.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('type')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.settings.entries.type'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-queue-list'),

                                Infolists\Components\TextEntry::make('reference')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.settings.entries.reference'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-identification'),

                                Infolists\Components\TextEntry::make('barcode')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.settings.entries.barcode'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-bars-4'),

                                Infolists\Components\TextEntry::make('category.full_name')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.settings.entries.category'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-folder'),

                                Infolists\Components\TextEntry::make('company.name')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.settings.entries.company'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-building-office'),
                            ]),

                        Infolists\Components\Section::make(__('sales::filament/clusters/products/resources/product.infolist.sections.pricing.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('price')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.pricing.entries.price'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-banknotes'),

                                Infolists\Components\TextEntry::make('cost')
                                    ->label(__('sales::filament/clusters/products/resources/product.infolist.sections.pricing.entries.cost'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-banknotes'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewProduct::class,
            Pages\EditProduct::class,
            Pages\ManageAttributes::class,
            Pages\ManageVariants::class,
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
            'index'      => Pages\ListProducts::route('/'),
            'create'     => Pages\CreateProduct::route('/create'),
            'view'       => Pages\ViewProduct::route('/{record}'),
            'edit'       => Pages\EditProduct::route('/{record}/edit'),
            'attributes' => Pages\ManageAttributes::route('/{record}/attributes'),
            'variants'   => Pages\ManageVariants::route('/{record}/variants'),
        ];
    }
}
