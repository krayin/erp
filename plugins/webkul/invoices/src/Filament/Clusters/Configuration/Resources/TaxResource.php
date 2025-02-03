<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;
use Webkul\Account\Models\Tax;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Forms;
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
        return __('Taxes');
    }

    public static function getNavigationLabel(): string
    {
        return __('Taxes');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Accounting');
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
            __('Company')     => $record->company?->name ?? '—',
            __('Name')        => $record->name ?? '—',
            __('Amount Type') => $record->amount_type ?? '—',
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
                                    ->label(__('Name'))
                                    ->required()
                                    ->placeholder(__('Name')),
                                Forms\Components\Select::make('type_tax_use')
                                    ->options(Enums\TypeTaxUse::options())
                                    ->label(__('Tax Type'))
                                    ->required(),
                                Forms\Components\Select::make('amount_type')
                                    ->options(Enums\AmountType::options())
                                    ->label(__('Tax Computation'))
                                    ->required(),
                                Forms\Components\Select::make('tax_scope')
                                    ->options(Enums\TaxScope::options())
                                    ->label(__('Tax Scope')),
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('Status'))
                                    ->inline(false),
                                Forms\Components\TextInput::make('amount')
                                    ->label(__('Amount'))
                                    ->suffix('%')
                                    ->numeric()
                                    ->required(),
                            ])->columns(2),
                        Forms\Components\Fieldset::make(__('Advanced Options'))
                            ->schema([
                                Forms\Components\TextInput::make('invoice_label')
                                    ->label(__('Invoice Label'))
                                    ->placeholder(__('Invoice Label')),
                                Forms\Components\Select::make('tax_group_id')
                                    ->relationship('taxGroup', 'name')
                                    ->label(__('Tax Group')),
                                Forms\Components\Select::make('country_id')
                                    ->relationship('country', 'name')
                                    ->label(__('Country')),
                                Forms\Components\Toggle::make('price_include_override')
                                    ->inline(false)
                                    ->label(__('Include in Price')),
                                Forms\Components\Toggle::make('include_base_amount')
                                    ->inline(false)
                                    ->label(__('Include base amount')),
                                Forms\Components\Toggle::make('is_base_affected')
                                    ->inline(false)
                                    ->label(__('Include base amount')),
                            ]),
                        Forms\Components\Fieldset::make(__('Description & Invoice Legal Notes'))
                            ->schema([
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('Description'))
                                    ->placeholder(__('Description')),
                                Forms\Components\RichEditor::make('invoice_legal_notes')
                                    ->label(__('Invoice Legal Notes'))
                                    ->placeholder(__('Invoice Legal Notes')),
                            ])->columns(1)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('Company'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taxGroup.name')
                    ->label(__('Tax Group'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('Country'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_tax_use')
                    ->label(__('Type Tax Use'))
                    ->searchable()
                    ->formatStateUsing(fn($state) => Enums\TypeTaxUse::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_scope')
                    ->label(__('Tax Scope'))
                    ->searchable()
                    ->formatStateUsing(fn($state) => Enums\TaxScope::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_type')
                    ->label(__('Amount Type'))
                    ->searchable()
                    ->formatStateUsing(fn($state) => Enums\AmountType::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_label')
                    ->label(__('Invoice Label'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_exigibility')
                    ->label(__('Invoice Label'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_include_override')
                    ->label(__('Price Include Override'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('Status'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('include_base_amount')
                    ->boolean()
                    ->label(__('Include Base Amount'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_base_affected')
                    ->boolean()
                    ->label(__('Is Base Affected'))
                    ->searchable()
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('Name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label(__('Company'))
                    ->collapsible(),
                Tables\Grouping\Group::make('taxGroup.name')
                    ->label(__('Tax Group'))
                    ->collapsible(),
                Tables\Grouping\Group::make('country.name')
                    ->label(__('Country'))
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label(__('Created By'))
                    ->collapsible(),
                Tables\Grouping\Group::make('type_tax_use')
                    ->label(__('Type tax use'))
                    ->collapsible(),
                Tables\Grouping\Group::make('tax_scope')
                    ->label(__('Tax Scope'))
                    ->collapsible(),
                Tables\Grouping\Group::make('amount_type')
                    ->label(__('Amount Type'))
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
