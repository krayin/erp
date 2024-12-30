<?php

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Contact\Filament\Resources\PartnerResource;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Partner\Enums\AddressType;

class ManageAddresses extends ManageRelatedRecords
{
    protected static string $resource = PartnerResource::class;

    protected static string $relationship = 'addresses';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationLabel(): string
    {
        return __('contacts::filament/resources/partner/pages/manage-addresses.title');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Radio::make('type')
                ->hiddenLabel()
                ->options([
                    AddressType::INVOICE->value => __('partners::enums/address-type.invoice'),
                    AddressType::DELIVERY->value => __('partners::enums/address-type.delivery'),
                    AddressType::OTHER->value => __('partners::enums/address-type.other'),
                ])
                ->default(AddressType::INVOICE->value)
                ->inline()
                ->columnSpan(2),
            Forms\Components\Select::make('country_id')
                ->label(__('contacts::filament/resources/partner/pages/manage-addresses.form.country'))
                ->relationship(name: 'country', titleAttribute: 'name')
                ->afterStateUpdated(fn (Forms\Set $set) => $set('state_id', null))
                ->searchable()
                ->preload()
                ->live(),
            Forms\Components\Select::make('state_id')
                ->label(__('contacts::filament/resources/partner/pages/manage-addresses.form.state'))
                ->relationship(
                    name: 'state',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Forms\Get $get, Builder $query) => $query->where('country_id', $get('country_id')),
                )
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('street1')
                ->label(__('contacts::filament/resources/partner/pages/manage-addresses.form.street1')),
            Forms\Components\TextInput::make('street2')
                ->label(__('contacts::filament/resources/partner/pages/manage-addresses.form.street2')),
            Forms\Components\TextInput::make('city')
                ->label(__('contacts::filament/resources/partner/pages/manage-addresses.form.city')),
            Forms\Components\TextInput::make('zip')
                ->label(__('contacts::filament/resources/partner/pages/manage-addresses.form.zip')),
            Forms\Components\Hidden::make('creator_id')
                ->default(Auth::user()->id),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label(__('contacts::filament/resources/partner/pages/manage-addresses.table.columns.type'))
                    ->formatStateUsing(fn (string $state): string => AddressType::options()[$state])
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('contacts::filament/resources/partner/pages/manage-addresses.table.columns.country'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label(__('contacts::filament/resources/partner/pages/manage-addresses.table.columns.state'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('street1')
                    ->label(__('contacts::filament/resources/partner/pages/manage-addresses.table.columns.street1'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('street2')
                    ->label(__('contacts::filament/resources/partner/pages/manage-addresses.table.columns.street2'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('contacts::filament/resources/partner/pages/manage-addresses.table.columns.city'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip')
                    ->label(__('contacts::filament/resources/partner/pages/manage-addresses.table.columns.zip'))
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('contacts::filament/resources/partner/pages/manage-addresses.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner/pages/manage-addresses.table.header-actions.create.notification.title'))
                            ->body(__('contacts::filament/resources/partner/pages/manage-addresses.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner/pages/manage-addresses.table.actions.edit.notification.title'))
                            ->body(__('contacts::filament/resources/partner/pages/manage-addresses.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner/pages/manage-addresses.table.actions.delete.notification.title'))
                            ->body(__('contacts::filament/resources/partner/pages/manage-addresses.table.actions.delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner/pages/manage-addresses.table.bulk-actions.delete.notification.title'))
                            ->body(__('contacts::filament/resources/partner/pages/manage-addresses.table.bulk-actions.delete.notification.body')),
                    ),
            ]);
    }
}
