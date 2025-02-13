<?php

namespace Webkul\Invoice\Traits;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

trait FiscalPositionTax
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tax_source_id')
                    ->relationship('taxSource', 'name')
                    ->label(__('invoices::traits/fiscal-position-tax.form.fields.tax-source'))
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('tax_destination_id')
                    ->relationship('taxDestination', 'name')
                    ->label(__('invoices::traits/fiscal-position-tax.form.fields.tax-destination'))
                    ->preload()
                    ->searchable()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('taxSource.name')
                    ->searchable()
                    ->sortable()
                    ->label(__('invoices::traits/fiscal-position-tax.table.columns.tax-source')),
                Tables\Columns\TextColumn::make('taxDestination.name')
                    ->searchable()
                    ->sortable()
                    ->label('Tax Destination')
                    ->label(__('invoices::traits/fiscal-position-tax.table.columns.tax-destination')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::traits/fiscal-position-tax.table.actions.edit.notification.title'))
                            ->title(__('invoices::traits/fiscal-position-tax.table.actions.edit.notification.body'))
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::traits/fiscal-position-tax.table.actions.delete.notification.title'))
                            ->title(__('invoices::traits/fiscal-position-tax.table.actions.delete.notification.body'))
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::traits/fiscal-position-tax.table.header-actions.create.notification.title'))
                            ->title(__('invoices::traits/fiscal-position-tax.table.header-actions.create.notification.body'))
                    )
                    ->mutateFormDataUsing(function ($data) {
                        $user = Auth::user();

                        $data['creator_id'] = $user->id;

                        $data['company_id'] = $user?->default_company_id;

                        return $data;
                    }),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('taxSource.name')
                    ->icon('heroicon-o-receipt-percent')
                    ->placeholder('-')
                    ->label(__('invoices::traits/fiscal-position-tax.infolist.entries.tax-source')),
                Infolists\Components\TextEntry::make('taxDestination.name')
                    ->placeholder('-')
                    ->icon('heroicon-o-receipt-percent')
                    ->label(__('invoices::traits/fiscal-position-tax.infolist.entries.tax-destination')),
            ]);
    }
}
