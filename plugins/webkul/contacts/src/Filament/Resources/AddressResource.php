<?php

namespace Webkul\Contact\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Partner\Enums\AddressType;
use Webkul\Partner\Models\Partner;

class AddressResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Radio::make('type')
                ->hiddenLabel()
                ->options([
                    AddressType::INVOICE->value  => __('partners::enums/address-type.invoice'),
                    AddressType::DELIVERY->value => __('partners::enums/address-type.delivery'),
                    AddressType::OTHER->value    => __('partners::enums/address-type.other'),
                ])
                ->default(AddressType::INVOICE->value)
                ->inline()
                ->columnSpan(2),
            Forms\Components\TextInput::make('name')
                ->label(__('contacts::filament/resources/address.form.name'))
                ->required(),
            Forms\Components\TextInput::make('email')
                ->label(__('contacts::filament/resources/address.form.email'))
                ->email(),
            Forms\Components\TextInput::make('phone')
                ->label(__('contacts::filament/resources/address.form.phone'))
                ->tel(),
            Forms\Components\Select::make('country_id')
                ->label(__('contacts::filament/resources/address.form.country'))
                ->relationship(name: 'country', titleAttribute: 'name')
                ->afterStateUpdated(fn (Forms\Set $set) => $set('state_id', null))
                ->searchable()
                ->preload()
                ->live(),
            Forms\Components\Select::make('state_id')
                ->label(__('contacts::filament/resources/address.form.state'))
                ->relationship(
                    name: 'state',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Forms\Get $get, Builder $query) => $query->where('country_id', $get('country_id')),
                )
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('street1')
                ->label(__('contacts::filament/resources/address.form.street1')),
            Forms\Components\TextInput::make('street2')
                ->label(__('contacts::filament/resources/address.form.street2')),
            Forms\Components\TextInput::make('city')
                ->label(__('contacts::filament/resources/address.form.city')),
            Forms\Components\TextInput::make('zip')
                ->label(__('contacts::filament/resources/address.form.zip')),
            Forms\Components\Hidden::make('creator_id')
                ->default(Auth::user()->id),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label(__('contacts::filament/resources/address.table.columns.type'))
                    ->formatStateUsing(fn (string $state): string => AddressType::options()[$state])
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('contacts::filament/resources/address.table.columns.country'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label(__('contacts::filament/resources/address.table.columns.state'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('street1')
                    ->label(__('contacts::filament/resources/address.table.columns.street1'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('street2')
                    ->label(__('contacts::filament/resources/address.table.columns.street2'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('contacts::filament/resources/address.table.columns.city'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip')
                    ->label(__('contacts::filament/resources/address.table.columns.zip'))
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('contacts::filament/resources/address.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/address.table.header-actions.create.notification.title'))
                            ->body(__('contacts::filament/resources/address.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/address.table.actions.edit.notification.title'))
                            ->body(__('contacts::filament/resources/address.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/address.table.actions.delete.notification.title'))
                            ->body(__('contacts::filament/resources/address.table.actions.delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/address.table.bulk-actions.delete.notification.title'))
                            ->body(__('contacts::filament/resources/address.table.bulk-actions.delete.notification.body')),
                    ),
            ]);
    }
}
