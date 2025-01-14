<?php

namespace Webkul\Inventory\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Inventory\Enums\ProductTracking;
use Webkul\Inventory\Filament\Resources\ProductResource\Pages;
use Webkul\Inventory\Models\Product;
use Webkul\Product\Enums\ProductType;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductCategoryResource\Pages\ManageProducts;

class ProductResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Product::class;

    protected static ?string $slug = 'inventory/products';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 2;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/resources/product.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/resources/product.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('inventories::filament/resources/product.form.sections.general.fields.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->placeholder(__('inventories::filament/resources/product.form.sections.general.fields.name-placeholder'))
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),

                                Forms\Components\RichEditor::make('description')
                                    ->label(__('inventories::filament/resources/product.form.sections.general.fields.description')),
                                Forms\Components\Select::make('tags')
                                    ->label(__('inventories::filament/resources/product.form.sections.general.fields.tags'))
                                    ->relationship(name: 'tags', titleAttribute: 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('inventories::filament/resources/product.form.sections.general.fields.name'))
                                            ->required()
                                            ->unique('products_tags'),
                                    ]),
                            ]),
                        Forms\Components\Section::make(__('inventories::filament/resources/product.form.sections.images.title'))
                            ->schema([
                                Forms\Components\FileUpload::make('images')
                                    ->multiple()
                                    ->storeFileNamesIn('products'),
                            ]),
                        Forms\Components\Section::make(__('inventories::filament/resources/product.form.sections.inventory.title'))
                            ->schema([
                                Forms\Components\Fieldset::make(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.tracking.title'))
                                    ->schema([
                                        Forms\Components\Toggle::make('is_storable')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.tracking.fields.track-inventory'))
                                            ->default(true)
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                                if (! $get('is_storable')) {
                                                    $set('tracking', ProductTracking::QTY->value);

                                                    $set('use_expiration_date', false);
                                                }
                                            }),
                                        Forms\Components\Select::make('tracking')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.tracking.fields.track-by'))
                                            ->selectablePlaceholder(false)
                                            ->options(ProductTracking::class)
                                            ->default(ProductTracking::QTY->value)
                                            ->visible(fn (Forms\Get $get): bool => (bool) $get('is_storable'))
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                                if ($get('tracking') == ProductTracking::QTY->value) {
                                                    $set('use_expiration_date', false);
                                                }
                                            }),
                                        Forms\Components\Toggle::make('use_expiration_date')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.tracking.fields.expiration-date'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/resources/product.form.sections.inventory.fieldsets.tracking.fields.expiration-date-hint-tooltip'))
                                            ->visible(fn (Forms\Get $get): bool => $get('tracking') != ProductTracking::QTY->value)
                                            ->live(),
                                    ])
                                    ->columns(1),
                                Forms\Components\Fieldset::make(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.operation.title'))
                                    ->schema([
                                        Forms\Components\CheckboxList::make('routes')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.operation.fields.routes'))
                                            ->relationship('routes', 'name')
                                            ->searchable()
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/resources/product.form.sections.inventory.fieldsets.logistics.fields.routes-hint-tooltip')),
                                    ]),

                                Forms\Components\Fieldset::make(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.logistics.title'))
                                    ->schema([
                                        Forms\Components\Select::make('responsible_id')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.logistics.fields.responsible'))
                                            ->relationship('responsible', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/resources/product.form.sections.inventory.fieldsets.logistics.fields.responsible-hint-tooltip')),
                                        Forms\Components\TextInput::make('weight')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.logistics.fields.weight'))
                                            ->numeric()
                                            ->minValue(0),
                                        Forms\Components\TextInput::make('volume')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.logistics.fields.volume'))
                                            ->numeric()
                                            ->minValue(0),
                                        Forms\Components\TextInput::make('sale_delay')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.logistics.fields.sale-delay'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/resources/product.form.sections.inventory.fieldsets.logistics.fields.sale-delay-hint-tooltip')),
                                    ]),

                                Forms\Components\Fieldset::make(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.title'))
                                    ->schema([
                                        Forms\Components\TextInput::make('expiration_time')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.fields.expiration-date'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.fields.expiration-date-hint-tooltip')),
                                        Forms\Components\TextInput::make('use_time')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.fields.best-before-date'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.fields.best-before-date-hint-tooltip')),
                                        Forms\Components\TextInput::make('removal_time')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.fields.removal-date'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.fields.removal-date-hint-tooltip')),
                                        Forms\Components\TextInput::make('alert_time')
                                            ->label(__('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.fields.alert-date'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/resources/product.form.sections.inventory.fieldsets.traceability.fields.alert-date-hint-tooltip')),
                                    ])
                                    ->visible(fn (Forms\Get $get): bool => (bool) $get('use_expiration_date')),
                            ])
                            ->visible(fn (Forms\Get $get): bool => $get('type') == ProductType::GOODS->value),

                        Forms\Components\Section::make(__('inventories::filament/resources/product.form.sections.additional.title'))
                            ->visible(! empty($customFormFields = static::getCustomFormFields()))
                            ->schema($customFormFields),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('inventories::filament/resources/product.form.sections.settings.title'))
                            ->schema([
                                Forms\Components\Radio::make('type')
                                    ->label(__('inventories::filament/resources/product.form.sections.settings.fields.type'))
                                    ->options(ProductType::class)
                                    ->default(ProductType::GOODS->value)
                                    ->live(),
                                Forms\Components\TextInput::make('reference')
                                    ->label(__('inventories::filament/resources/product.form.sections.settings.fields.reference'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('barcode')
                                    ->label(__('inventories::filament/resources/product.form.sections.settings.fields.barcode'))
                                    ->maxLength(255),
                                Forms\Components\Select::make('category_id')
                                    ->label(__('inventories::filament/resources/product.form.sections.settings.fields.category'))
                                    ->required()
                                    ->relationship('category', 'full_name')
                                    ->searchable()
                                    ->preload()
                                    ->hiddenOn(ManageProducts::class),
                                Forms\Components\Select::make('company_id')
                                    ->label(__('inventories::filament/resources/product.form.sections.settings.fields.company'))
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::user()->default_company_id),
                            ]),
                        Forms\Components\Section::make(__('inventories::filament/resources/product.form.sections.pricing.title'))
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label(__('inventories::filament/resources/product.form.sections.pricing.fields.price'))
                                    ->numeric()
                                    ->required()
                                    ->default(0.00),
                                Forms\Components\TextInput::make('cost')
                                    ->label(__('inventories::filament/resources/product.form.sections.pricing.fields.cost'))
                                    ->numeric()
                                    ->default(0.00),
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
                    ->icon(fn (Product $record): string => $record->is_favorite ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (Product $record): string => $record->is_favorite ? 'warning' : 'gray')
                    ->action(function (Product $record): void {
                        $record->update([
                            'is_favorite' => ! $record->is_favorite,
                        ]);
                    }),
                Tables\Columns\ImageColumn::make('images')
                    ->label(__('inventories::filament/resources/product.table.columns.images'))
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(isSeparate: true),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/resources/product.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->label(__('inventories::filament/resources/product.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->label(__('inventories::filament/resources/product.table.columns.tags'))
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('responsible.name')
                    ->label(__('inventories::filament/resources/product.table.columns.responsible'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->label(__('inventories::filament/resources/product.table.columns.barcode'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/resources/product.table.columns.company'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('inventories::filament/resources/product.table.columns.price'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label(__('inventories::filament/resources/product.table.columns.cost'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('inventories::filament/resources/product.table.columns.category'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('inventories::filament/resources/product.table.columns.type'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('inventories::filament/resources/product.table.columns.deleted-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/resources/product.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/resources/product.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('type')
                    ->label(__('inventories::filament/resources/product.table.groups.type')),
                Tables\Grouping\Group::make('category.name')
                    ->label(__('inventories::filament/resources/product.table.groups.category')),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/resources/product.table.groups.created-at'))
                    ->date(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort', 'desc')
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints(collect(static::mergeCustomTableQueryBuilderConstraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label(__('inventories::filament/resources/product.table.filters.name')),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('reference')
                            ->label(__('inventories::filament/resources/product.table.filters.reference'))
                            ->icon('heroicon-o-link'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('barcode')
                            ->label(__('inventories::filament/resources/product.table.filters.barcode'))
                            ->icon('heroicon-o-bars-4'),
                        Tables\Filters\QueryBuilder\Constraints\BooleanConstraint::make('is_favorite')
                            ->label(__('inventories::filament/resources/product.table.filters.is-favorite'))
                            ->icon('heroicon-o-star'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('price')
                            ->label(__('inventories::filament/resources/product.table.filters.price'))
                            ->icon('heroicon-o-banknotes'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('cost')
                            ->label(__('inventories::filament/resources/product.table.filters.cost'))
                            ->icon('heroicon-o-banknotes'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('weight')
                            ->label(__('inventories::filament/resources/product.table.filters.weight'))
                            ->icon('heroicon-o-scale'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('volume')
                            ->label(__('inventories::filament/resources/product.table.filters.volume'))
                            ->icon('heroicon-o-beaker'),
                        Tables\Filters\QueryBuilder\Constraints\SelectConstraint::make('type')
                            ->label(__('inventories::filament/resources/product.table.filters.type'))
                            ->multiple()
                            ->options(ProductType::class)
                            ->icon('heroicon-o-queue-list'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('tags')
                            ->label(__('inventories::filament/resources/product.table.filters.tags'))
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
                            ->label(__('inventories::filament/resources/product.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('inventories::filament/resources/product.table.filters.updated-at')),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('responsible')
                            ->label(__('inventories::filament/resources/product.table.filters.responsible'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label(__('inventories::filament/resources/product.table.filters.company'))
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
                            ->label(__('inventories::filament/resources/product.table.filters.creator'))
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
                fn (Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/resources/product.table.actions.restore.notification.title'))
                                ->body(__('inventories::filament/resources/product.table.actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/resources/product.table.actions.delete.notification.title'))
                                ->body(__('inventories::filament/resources/product.table.actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/resources/product.table.actions.force-delete.notification.title'))
                                ->body(__('inventories::filament/resources/product.table.actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/resources/product.table.bulk-actions.restore.notification.title'))
                                ->body(__('inventories::filament/resources/product.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/resources/product.table.bulk-actions.delete.notification.title'))
                                ->body(__('inventories::filament/resources/product.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/resources/product.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('inventories::filament/resources/product.table.bulk-actions.force-delete.notification.body')),
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
                                    ->label(__('inventories::filament/resources/product.infolist.sections.general.entries.name')),

                                Infolists\Components\TextEntry::make('description')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.general.entries.description'))
                                    ->html()
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('tags.name')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.general.entries.tags'))
                                    ->badge()
                                    ->separator(', ')
                                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                            ]),

                        Infolists\Components\Section::make(__('inventories::filament/resources/product.infolist.sections.images.title'))
                            ->schema([
                                Infolists\Components\ImageEntry::make('images')
                                    ->hiddenLabel()
                                    ->circular(),
                            ])
                            ->visible(fn ($record): bool => ! empty($record->images)),

                        Infolists\Components\Section::make(__('inventories::filament/resources/product.infolist.sections.inventory.title'))
                            ->schema([
                                Infolists\Components\Grid::make(3)
                                    ->schema([
                                        Infolists\Components\IconEntry::make('is_storable')
                                            ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.tracking.entries.track-inventory'))
                                            ->boolean(),

                                        Infolists\Components\TextEntry::make('tracking')
                                            ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.tracking.entries.track-by')),

                                        Infolists\Components\IconEntry::make('use_expiration_date')
                                            ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.tracking.entries.expiration-date'))
                                            ->boolean(),
                                    ]),

                                Infolists\Components\Section::make(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.operation.title'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('routes.name')
                                            ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.operation.entries.routes'))
                                            ->icon('heroicon-o-arrow-path')
                                            ->listWithLineBreaks()
                                            ->placeholder('—'),
                                    ]),

                                Infolists\Components\Section::make(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.logistics.title'))
                                    ->schema([
                                        Infolists\Components\Grid::make(2)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('responsible.name')
                                                    ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.logistics.entries.responsible'))
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-user'),

                                                Infolists\Components\TextEntry::make('weight')
                                                    ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.logistics.entries.weight'))
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-scale'),

                                                Infolists\Components\TextEntry::make('volume')
                                                    ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.logistics.entries.volume'))
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-beaker'),

                                                Infolists\Components\TextEntry::make('sale_delay')
                                                    ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.logistics.entries.sale-delay'))
                                                    ->placeholder('—'),
                                            ]),
                                    ]),

                                Infolists\Components\Section::make(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.traceability.title'))
                                    ->schema([
                                        Infolists\Components\Grid::make(2)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('expiration_time')
                                                    ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.traceability.entries.expiration-date'))
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-clock'),

                                                Infolists\Components\TextEntry::make('use_time')
                                                    ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.traceability.entries.best-before-date'))
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-clock'),

                                                Infolists\Components\TextEntry::make('removal_time')
                                                    ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.traceability.entries.removal-date'))
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-clock'),

                                                Infolists\Components\TextEntry::make('alert_time')
                                                    ->label(__('inventories::filament/resources/product.infolist.sections.inventory.fieldsets.traceability.entries.alert-date'))
                                                    ->placeholder('—')
                                                    ->icon('heroicon-o-clock'),
                                            ]),
                                    ])
                                    ->visible(fn ($record): bool => (bool) $record->use_expiration_date),
                            ])
                            ->visible(fn ($record): bool => $record->type == ProductType::GOODS),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/resources/product.infolist.sections.record-information.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-o-user'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.record-information.entries.updated-at'))
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar'),
                            ]),

                        Infolists\Components\Section::make(__('inventories::filament/resources/product.infolist.sections.settings.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('type')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.settings.entries.type'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-queue-list'),

                                Infolists\Components\TextEntry::make('reference')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.settings.entries.reference'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-identification'),

                                Infolists\Components\TextEntry::make('barcode')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.settings.entries.barcode'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-bars-4'),

                                Infolists\Components\TextEntry::make('category.full_name')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.settings.entries.category'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-folder'),

                                Infolists\Components\TextEntry::make('company.name')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.settings.entries.company'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-building-office'),
                            ]),

                        Infolists\Components\Section::make(__('inventories::filament/resources/product.infolist.sections.pricing.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('price')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.pricing.entries.price'))
                                    ->placeholder('—')
                                    ->icon('heroicon-o-banknotes'),

                                Infolists\Components\TextEntry::make('cost')
                                    ->label(__('inventories::filament/resources/product.infolist.sections.pricing.entries.cost'))
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
