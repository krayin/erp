<?php

namespace Webkul\Invoice\Traits;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Filament\Tables;
use Webkul\Invoice\Enums\DelayType;
use Webkul\Invoice\Enums\DueTermValue;

trait PaymentDueTerm
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('value')
                    ->options(DueTermValue::class)
                    ->label(__('invoices::traits/payment-due-term.form.value'))
                    ->required(),
                Forms\Components\TextInput::make('value_amount')
                    ->label(__('invoices::traits/payment-due-term.form.due'))
                    ->default(100)
                    ->numeric(),
                Forms\Components\Select::make('delay_type')
                    ->options(DelayType::class)
                    ->label(__('invoices::traits/payment-due-term.form.delay-type'))
                    ->required(),
                Forms\Components\TextInput::make('days_next_month')
                    ->default(10)
                    ->label(__('invoices::traits/payment-due-term.form.days-on-the-next-month')),
                Forms\Components\TextInput::make('nb_days')
                    ->default(0)
                    ->numeric()
                    ->label(__('invoices::traits/payment-due-term.form.days')),
                Forms\Components\Select::make('payment_id')
                    ->relationship('paymentTerm', 'name')
                    ->label(__('invoices::traits/payment-due-term.form.payment-term'))
                    ->searchable()
                    ->preload()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value_amount')
                    ->label(__('invoices::traits/payment-due-term.table.columns.due'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label(__('invoices::traits/payment-due-term.table.columns.value'))
                    ->formatStateUsing(fn($state) => DueTermValue::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('value_amount')
                    ->label(__('invoices::traits/payment-due-term.table.columns.value-amount'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('nb_days')
                    ->label(__('invoices::traits/payment-due-term.table.columns.after'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('delay_type')
                    ->formatStateUsing(fn($state) => DelayType::options()[$state])
                    ->label(__('invoices::traits/payment-due-term.table.columns.delay-type'))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('invoices::traits/payment-due-term.table.actions.edit.notification.title')
                            ->body('invoices::traits/payment-due-term.table.actions.edit.notification.body')
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('invoices::traits/payment-due-term.table.actions.delete.notification.title')
                            ->body('invoices::traits/payment-due-term.table.actions.delete.notification.body')
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('invoices::traits/payment-due-term.table.actions.delete.notification.title')
                            ->body('invoices::traits/payment-due-term.table.actions.delete.notification.body')
                    )
                    ->icon('heroicon-o-plus-circle')
            ]);
    }
}
