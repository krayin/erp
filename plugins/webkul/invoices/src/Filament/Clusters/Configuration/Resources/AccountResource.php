<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\AccountResource\Pages;
use Webkul\Invoice\Models\Account;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Invoice\Enums\AccountType;

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
                                    ->searchable()
                            ])
                    ])->columns(2)
            ]);
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'view' => Pages\ViewAccount::route('/{record}'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
