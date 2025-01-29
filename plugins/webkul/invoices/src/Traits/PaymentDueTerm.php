<?php

namespace Webkul\Invoice\Traits;

use Filament\Forms\Form;
use Filament\Forms;
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
                    ->label(__('Value'))
                    ->required(),
                Forms\Components\TextInput::make('value_amount')
                    ->label(__('Due'))
                    ->default(100)
                    ->numeric(),
                Forms\Components\Select::make('delay_type')
                    ->options(DelayType::class)
                    ->label(__('Delay Type'))
                    ->required(),
                Forms\Components\TextInput::make('days_next_month')
                    ->default(10)
                    ->label(__('Days on the next month')),
                Forms\Components\TextInput::make('nb_days')
                    ->default(0)
                    ->numeric()
                    ->label(__('Days')),
                Forms\Components\Select::make('payment_id')
                    ->relationship('paymentTerm', 'name')
                    ->label(__('Payment Terms'))
                    ->searchable()
                    ->preload()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value_amount')
                    ->label(__('Due'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label(__('Value'))
                    ->formatStateUsing(fn($state) => DueTermValue::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('value_amount')
                    ->label(__('Value Amount'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('nb_days')
                    ->label(__('After'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('delay_type')
                    ->formatStateUsing(fn($state) => DelayType::options()[$state])
                    ->label(__('Delay Type'))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Create Due Term'))
                    ->icon('heroicon-o-plus-circle')
            ]);
    }
}
