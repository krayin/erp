<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\BankAccountResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Partner\Models\BankAccount;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $cluster = Configuration::class;

    public static function getNavigationGroup(): string
    {
        return __('invoices::filament/clusters/configurations/resources/bank-account.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/bank-account.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('account_number')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.account-number'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('bank_id')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.title'))
                    ->relationship('bank', 'name')
                    ->required()
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\Section::make(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.general.title'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.general.fields.name'))
                                    ->maxLength(255)
                                    ->required(),
                                Forms\Components\TextInput::make('code')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.general.fields.code'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.general.fields.email'))
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.general.fields.phone'))
                                    ->tel()
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                        Forms\Components\Section::make(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.address.title'))
                            ->schema([
                                Forms\Components\Select::make('country_id')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.address.fields.country'))
                                    ->relationship(name: 'country', titleAttribute: 'name')
                                    ->afterStateUpdated(fn(Forms\Set $set) => $set('state_id', null))
                                    ->searchable()
                                    ->preload()
                                    ->live(),
                                Forms\Components\Select::make('state_id')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.address.fields.state'))
                                    ->relationship(
                                        name: 'state',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn(Forms\Get $get, Builder $query) => $query->where('country_id', $get('country_id')),
                                    )
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('street1')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.address.fields.street1')),
                                Forms\Components\TextInput::make('street2')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.address.fields.street2')),
                                Forms\Components\TextInput::make('city')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.address.fields.city')),
                                Forms\Components\TextInput::make('zip')
                                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.bank.sections.address.fields.zip')),
                                Forms\Components\Hidden::make('creator_id')
                                    ->default(Auth::user()->id),
                            ])
                            ->columns(2),
                    ])
                    ->preload(),
                Forms\Components\Select::make('partner_id')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.form.account-holder'))
                    ->relationship('partner', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_number')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.columns.account-number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.columns.bank'))
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.columns.account-holder'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.columns.deleted-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('bank.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.groups.bank')),
                Tables\Grouping\Group::make('can_send_money')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.groups.can-send-money')),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.groups.created-at'))
                    ->date(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('can_send_money')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.filters.can-send-money')),
                Tables\Filters\SelectFilter::make('bank_id')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.filters.bank'))
                    ->relationship('bank', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('partner_id')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.filters.account-holder'))
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('creator_id')
                    ->label(__('invoices::filament/clusters/configurations/resources/bank-account.table.filters.creator'))
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->trashed())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::filament/clusters/configurations/resources/bank-account.table.actions.edit.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/bank-account.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::filament/clusters/configurations/resources/bank-account.table.actions.restore.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/bank-account.table.actions.restore.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::filament/clusters/configurations/resources/bank-account.table.actions.delete.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/bank-account.table.actions.delete.notification.body')),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::filament/clusters/configurations/resources/bank-account.table.actions.force-delete.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/bank-account.table.actions.force-delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('invoices::filament/clusters/configurations/resources/bank-account.table.bulk-actions.restore.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/bank-account.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('invoices::filament/clusters/configurations/resources/bank-account.table.bulk-actions.delete.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/bank-account.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('invoices::filament/clusters/configurations/resources/bank-account.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/bank-account.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankAccounts::route('/'),
        ];
    }
}
