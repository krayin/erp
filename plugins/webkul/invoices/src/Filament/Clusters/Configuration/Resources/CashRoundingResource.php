<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\CashRoundingResource\Pages;
use Webkul\Account\Models\CashRounding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Account\Enums\RoundingMethod;
use Webkul\Account\Enums\RoundingStrategy;

class CashRoundingResource extends Resource
{
    protected static ?string $model = CashRounding::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/cash-rounding.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/cash-rounding.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('invoices::filament/clusters/configurations/resources/cash-rounding.navigation.group');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('invoices::filament/clusters/configurations/resources/cash-rounding.global-search.name') => $record->name ?? '—',
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
                                    ->required()
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.form.fields.name'))
                                    ->autofocus(),
                                Forms\Components\TextInput::make('rounding')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.form.fields.rounding-precision'))
                                    ->required()
                                    ->numeric()
                                    ->default(0.01),
                                Forms\Components\Select::make('strategy')
                                    ->options(RoundingStrategy::class)
                                    ->default(RoundingStrategy::BIGGEST_TAX->value)
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.form.fields.rounding-strategy')),
                                Forms\Components\Select::make('profit_account_id')
                                    ->relationship('profitAccount', 'name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.form.fields.profit-account'))
                                    ->required()
                                    ->autofocus(),
                                Forms\Components\Select::make('loss_account_id')
                                    ->relationship('lossAccount', 'name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.form.fields.loss-account'))
                                    ->required()
                                    ->autofocus(),
                                Forms\Components\Select::make('rounding_method')
                                    ->options(RoundingMethod::class)
                                    ->default(RoundingMethod::HALF_UP->value)
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.form.fields.rounding-method'))
                                    ->required()
                                    ->autofocus(),
                            ])
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('strategy')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.columns.rounding-strategy'))
                    ->formatStateUsing(fn($state) => RoundingStrategy::options()[$state] ?? $state)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rounding_method')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.columns.rounding-method'))
                    ->formatStateUsing(fn($state) => RoundingMethod::options()[$state] ?? $state)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.columns.created-by'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('profitAccount.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.columns.profit-account'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lossAccount.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.columns.loss-account'))
                    ->searchable()
                    ->sortable()
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.groups.name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('rounding_strategy')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.groups.rounding-strategy'))
                    ->collapsible(),
                Tables\Grouping\Group::make('rounding_method')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.groups.rounding-method'))
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.groups.created-by'))
                    ->collapsible(),
                Tables\Grouping\Group::make('profitAccount.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.groups.profit-account'))
                    ->collapsible(),
                Tables\Grouping\Group::make('lossAccount.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.groups.loss-account'))
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.actions.delete.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.actions.delete.notification.body'))
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.actions.delete.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/cash-rounding.table.actions.delete.notification.body'))
                        ),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.infolist.entries.name'))
                                    ->icon('heroicon-o-document-text'),
                                Infolists\Components\TextEntry::make('rounding')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.infolist.entries.rounding-precision'))
                                    ->icon('heroicon-o-calculator')
                                    ->numeric(
                                        decimalPlaces: 2,
                                        decimalSeparator: '.',
                                        thousandsSeparator: ','
                                    ),
                                Infolists\Components\TextEntry::make('strategy')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.infolist.entries.rounding-strategy'))
                                    ->icon('heroicon-o-cog')
                                    ->formatStateUsing(fn(string $state): string => RoundingStrategy::options()[$state]),
                                Infolists\Components\TextEntry::make('profitAccount.name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.infolist.entries.profit-account'))
                                    ->icon('heroicon-o-arrow-trending-up'),
                                Infolists\Components\TextEntry::make('lossAccount.name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.infolist.entries.loss-account'))
                                    ->icon('heroicon-o-arrow-trending-down'),
                                Infolists\Components\TextEntry::make('rounding_method')
                                    ->label(__('invoices::filament/clusters/configurations/resources/cash-rounding.infolist.entries.rounding-method'))
                                    ->icon('heroicon-o-adjustments-horizontal')
                                    ->formatStateUsing(fn(string $state): string => RoundingMethod::options()[$state]),
                            ])->columns(2),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCashRoundings::route('/'),
            'create' => Pages\CreateCashRounding::route('/create'),
            'view'   => Pages\ViewCashRounding::route('/{record}'),
            'edit'   => Pages\EditCashRounding::route('/{record}/edit'),
        ];
    }
}
