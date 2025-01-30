<?php

namespace Webkul\Invoice\Traits;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Set;
use Webkul\Invoice\Enums;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Webkul\Invoice\Models\TaxPartition as TaxPartitionModel;

trait TaxPartition
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('factor_percent')
                    ->suffix('%')
                    ->numeric()
                    ->label('Factor Percent')
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('factor', (float) $state / 100);
                    }),
                Forms\Components\TextInput::make('factor')
                    ->readOnly()
                    ->label('Factor Ratio'),
                Forms\Components\Select::make('repartition_type')
                    ->options(Enums\RepartitionType::options())
                    ->required()
                    ->label('Repartition Type'),
                Forms\Components\Select::make('document_type')
                    ->options(Enums\DocumentType::options())
                    ->required()
                    ->label('Document Type'),
                Forms\Components\Select::make('account_id')
                    ->relationship('account', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Account'),
                Forms\Components\Select::make('tax_id')
                    ->relationship('tax', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Tax'),
                Forms\Components\Toggle::make('use_in_tax_closing')
                    ->label('Tax Closing Entry'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('factor_percent')
                    ->label('Factor Percent(%)'),
                Tables\Columns\TextColumn::make('account.name')
                    ->label('Account'),
                Tables\Columns\TextColumn::make('tax.name')
                    ->label('Tax'),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company'),
                Tables\Columns\TextColumn::make('repartition_type')
                    ->formatStateUsing(fn($state) => Enums\RepartitionType::options()[$state])
                    ->label('Company'),
                Tables\Columns\TextColumn::make('document_type')
                    ->formatStateUsing(fn($state) => Enums\DocumentType::options()[$state])
                    ->label('Document Type'),
                Tables\Columns\IconColumn::make('use_in_tax_closing')
                    ->boolean()
                    ->label('Tax Closing Entry'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function ($data) {
                        $user = Auth::user();

                        $data['creator_id'] = $user->id;

                        $data['company_id'] = $user->default_company_id;

                        $data['factor'] = (float) $data['factor_percent'] / 100;

                        $data['sort'] = TaxPartitionModel::max('sort') + 1;

                        return $data;
                    }),
            ])
            ->reorderable('sort');
    }
}
