<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;
use Webkul\Account\Models\Tax;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Account\Enums;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\RelationManagers;

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $cluster = Configuration::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function getModelLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/tax.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/tax.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('invoices::filament/clusters/configurations/resources/tax.navigation.group');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'company.name',
            'name',
            'amount_type',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('invoices::filament/clusters/configurations/resources/tax.global-search.company')     => $record->company?->name ?? '—',
            __('invoices::filament/clusters/configurations/resources/tax.global-search.name')        => $record->name ?? '—',
            __('invoices::filament/clusters/configurations/resources/tax.global-search.amount-type') => $record->amount_type ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.fields.name'))
                                    ->required(),
                                Forms\Components\Select::make('type_tax_use')
                                    ->options(Enums\TypeTaxUse::options())
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.fields.tax-type'))
                                    ->required(),
                                Forms\Components\Select::make('amount_type')
                                    ->options(Enums\AmountType::options())
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.fields.tax-computation'))
                                    ->required(),
                                Forms\Components\Select::make('tax_scope')
                                    ->options(Enums\TaxScope::options())
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.fields.tax-scope')),
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.fields.status'))
                                    ->inline(false),
                                Forms\Components\TextInput::make('amount')
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.fields.amount'))
                                    ->suffix('%')
                                    ->numeric()
                                    ->required(),
                            ])->columns(2),
                        Forms\Components\Fieldset::make(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.advanced-options.title'))
                            ->schema([
                                Forms\Components\TextInput::make('invoice_label')
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.advanced-options.fields.invoice-label')),
                                Forms\Components\Select::make('tax_group_id')
                                    ->relationship('taxGroup', 'name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.advanced-options.fields.tax-group')),
                                Forms\Components\Select::make('country_id')
                                    ->relationship('country', 'name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.advanced-options.fields.country')),
                                Forms\Components\Toggle::make('price_include_override')
                                    ->inline(false)
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.advanced-options.fields.include-in-price')),
                                Forms\Components\Toggle::make('include_base_amount')
                                    ->inline(false)
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.advanced-options.fields.include-base-amount')),
                                Forms\Components\Toggle::make('is_base_affected')
                                    ->inline(false)
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.advanced-options.fields.is-base-affected')),
                            ]),
                        Forms\Components\Fieldset::make(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.description-and-legal-notes.title'))
                            ->schema([
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.description-and-legal-notes.fields.description')),
                                Forms\Components\RichEditor::make('invoice_legal_notes')
                                    ->label(__('invoices::filament/clusters/configurations/resources/tax.form.sections.field-set.description-and-legal-notes.fields.legal-notes')),
                            ])->columns(1)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.company'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taxGroup.name')
                    ->label(__('Tax Group'))
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.tax-group'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.country'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_tax_use')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.type-tax-use'))
                    ->searchable()
                    ->formatStateUsing(fn($state) => Enums\TypeTaxUse::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_scope')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.tax-scope'))
                    ->searchable()
                    ->formatStateUsing(fn($state) => Enums\TaxScope::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_type')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.amount-type'))
                    ->searchable()
                    ->formatStateUsing(fn($state) => Enums\AmountType::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_label')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.invoice-label'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_exigibility')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.tax-exigibility'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_include_override')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.price-include-override'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.amount'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.status'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('include_base_amount')
                    ->boolean()
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.include-base-amount'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_base_affected')
                    ->boolean()
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.columns.is-base-affected'))
                    ->searchable()
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.groups.name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.groups.company'))
                    ->collapsible(),
                Tables\Grouping\Group::make('taxGroup.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.groups.tax-group'))
                    ->collapsible(),
                Tables\Grouping\Group::make('country.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.groups.country'))
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.groups.created-by'))
                    ->collapsible(),
                Tables\Grouping\Group::make('type_tax_use')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.groups.type-tax-use'))
                    ->collapsible(),
                Tables\Grouping\Group::make('tax_scope')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.groups.tax-scope'))
                    ->collapsible(),
                Tables\Grouping\Group::make('amount_type')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax.table.groups.amount-type'))
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('invoices::filament/clusters/configurations/resources/tax.table.actions.delete.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/tax.table.actions.delete.notification.body'))
                        ),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('invoices::filament/clusters/configurations/resources/tax.table.bulk-actions.delete.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/tax.table.bulk-actions.delete.notification.body'))
                        ),
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
                                Infolists\Components\Section::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->icon('heroicon-o-document-text')
                                            ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.entries.name'))
                                            ->placeholder('—'),
                                        Infolists\Components\TextEntry::make('type_tax_use')
                                            ->icon('heroicon-o-calculator')
                                            ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.entries.tax-type'))
                                            ->placeholder('—'),
                                        Infolists\Components\TextEntry::make('amount_type')
                                            ->icon('heroicon-o-calculator')
                                            ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.entries.tax-computation'))
                                            ->placeholder('—'),
                                        Infolists\Components\TextEntry::make('tax_scope')
                                            ->icon('heroicon-o-globe-alt')
                                            ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.entries.tax-scope'))
                                            ->placeholder('—'),
                                        Infolists\Components\TextEntry::make('amount')
                                            ->icon('heroicon-o-currency-dollar')
                                            ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.entries.amount'))
                                            ->suffix('%')
                                            ->placeholder('—'),
                                        Infolists\Components\IconEntry::make('is_active')
                                            ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.entries.status')),
                                    ])->columns(2),
                                Infolists\Components\Section::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('description')
                                            ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.field-set.description-and-legal-notes.entries.description'))
                                            ->markdown()
                                            ->placeholder('—')
                                            ->columnSpanFull(),
                                        Infolists\Components\TextEntry::make('invoice_legal_notes')
                                            ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.field-set.description-and-legal-notes.entries.legal-notes'))
                                            ->markdown()
                                            ->placeholder('—')
                                            ->columnSpanFull(),
                                    ]),
                            ])->columnSpan(2),
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make()
                                ->schema([
                                    Infolists\Components\TextEntry::make('invoice_label')
                                        ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.field-set.advanced-options.entries.invoice-label'))
                                        ->placeholder('—'),
                                    Infolists\Components\TextEntry::make('taxGroup.name')
                                        ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.field-set.advanced-options.entries.tax-group'))
                                        ->placeholder('—'),
                                    Infolists\Components\TextEntry::make('country.name')
                                        ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.field-set.advanced-options.entries.country'))
                                        ->placeholder('—'),
                                    Infolists\Components\IconEntry::make('price_include_override')
                                        ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.field-set.advanced-options.entries.include-in-price')),
                                    Infolists\Components\IconEntry::make('include_base_amount')
                                        ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.field-set.advanced-options.entries.include-base-amount')),
                                    Infolists\Components\IconEntry::make('is_base_affected')
                                        ->label(__('invoices::filament/clusters/configurations/resources/tax.infolist.sections.field-set.advanced-options.entries.is-base-affected')),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewTax::class,
            Pages\EditTax::class,
            Pages\ManageDistributionForInvoice::class,
            Pages\ManageDistributionForRefund::class,
        ]);
    }

    public static function getRelations(): array
    {
        $relations = [
            RelationGroup::make('distribution_for_invoice', [
                RelationManagers\DistributionForInvoiceRelationManager::class,
            ])
                ->icon('heroicon-o-banknotes'),
            RelationGroup::make('distribution_for_refund', [
                RelationManagers\DistributionForRefundRelationManager::class,
            ])
                ->icon('heroicon-o-banknotes'),
        ];

        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index'                           => Pages\ListTaxes::route('/'),
            'create'                          => Pages\CreateTax::route('/create'),
            'view'                            => Pages\ViewTax::route('/{record}'),
            'edit'                            => Pages\EditTax::route('/{record}/edit'),
            'manage-distribution-for-invoice' => Pages\ManageDistributionForInvoice::route('/{record}/manage-distribution-for-invoice'),
            'manage-distribution-for-refunds' => Pages\ManageDistributionForRefund::route('/{record}/manage-distribution-for-refunds'),
        ];
    }
}
