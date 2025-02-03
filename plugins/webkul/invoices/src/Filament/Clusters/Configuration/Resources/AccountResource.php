<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\AccountResource\Pages;
use Webkul\Account\Models\Account;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Account\Enums\AccountType;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Account');
    }

    public static function getNavigationLabel(): string
    {
        return __('Accounts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Accounting');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'currency.name',
            'account_type',
            'name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Currency') => $record->currency?->name ?? '—',
            __('Name')    => $record->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->label(__('Code'))
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label(__('Account Name'))
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Fieldset::make('Accounting')
                            ->schema([
                                Forms\Components\Select::make('account_type')
                                    ->options(AccountType::options())
                                    ->preload()
                                    ->live()
                                    ->searchable(),
                                Forms\Components\Select::make('invoices_account_tax')
                                    ->relationship('taxes', 'name')
                                    ->label(__('Default Taxes'))
                                    ->hidden(fn(Get $get) => $get('account_type') === AccountType::OFF_BALANCE->value)
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),
                                Forms\Components\Select::make('invoices_account_account_tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),
                                Forms\Components\Select::make('invoices_account_journals')
                                    ->relationship('journals', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),
                                Forms\Components\Select::make('currency_id')
                                    ->relationship('currency', 'name')
                                    ->preload()
                                    ->searchable(),
                                Forms\Components\Toggle::make('deprecated')
                                    ->inline(false)
                                    ->label('Deprecated'),
                                Forms\Components\Toggle::make('reconcile')
                                    ->inline(false)
                                    ->label('Reconcile'),
                                Forms\Components\Toggle::make('non_trade')
                                    ->inline(false)
                                    ->label('Non Trade'),
                            ])
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->label(__('Code')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(__('Account Name')),
                Tables\Columns\TextColumn::make('account_type')
                    ->searchable()
                    ->label(__('Account Type')),

                Tables\Columns\TextColumn::make('currency.name')
                    ->searchable()
                    ->label(__('Currency')),
                Tables\Columns\IconColumn::make('deprecated')
                    ->boolean()
                    ->label('Deprecated'),
                Tables\Columns\IconColumn::make('reconcile')
                    ->boolean()
                    ->label('Reconcile'),
                Tables\Columns\IconColumn::make('non_trade')
                    ->boolean()
                    ->label('Non Trade'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'view' => Pages\ViewAccount::route('/{record}'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
