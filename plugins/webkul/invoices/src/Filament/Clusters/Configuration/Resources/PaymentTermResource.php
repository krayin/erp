<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Invoice\Enums\EarlyPayDiscount;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource\RelationManagers;
use Filament\Resources\Pages\Page;
use Webkul\Invoice\Models\PaymentTerm;

class PaymentTermResource extends Resource
{
    protected static ?string $model = PaymentTerm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Payment Term');
    }

    public static function getNavigationLabel(): string
    {
        return __('Payment Terms');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'company.name',
            'name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Company') => $record->company?->name ?? '—',
            __('Name') => $record->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label(__('Payment Terms'))
                                    ->maxLength(255)
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;'])
                                    ->columnSpan(1),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Toggle::make('early_discount')
                                    ->live()
                                    ->inline(false)
                                    ->label(__('Early Discount')),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->visible(fn(Get $get) => $get('early_discount'))
                            ->schema([
                                Forms\Components\TextInput::make('discount_percentage')
                                    ->required()
                                    ->suffix(__('%'))
                                    ->hiddenLabel(),
                                Forms\Components\TextInput::make('discount_days')
                                    ->required()
                                    ->prefix(__('if paid within'))
                                    ->suffix(__('days'))
                                    ->hiddenLabel(),
                            ])->columns(4),
                        Forms\Components\Group::make()
                            ->visible(fn(Get $get) => $get('early_discount'))
                            ->schema([
                                Forms\Components\Select::make('early_pay_discount')
                                    ->label(__('Reduced tax'))
                                    ->options(EarlyPayDiscount::class)
                                    ->default(EarlyPayDiscount::INCLUDED->value)
                            ])->columns(2),
                        Forms\Components\RichEditor::make('note')
                            ->label(__('Note')),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->inline(false)
                                    ->label(__('Status'))
                            ])->columns(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('Company'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_days')
                    ->label(__('Discount Days'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('early_pay_discount')
                    ->label(__('Early Pay Discount'))
                    ->searchable()
                    ->formatStateUsing(fn($state) => EarlyPayDiscount::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('Status'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('display_on_invoice')
                    ->boolean()
                    ->label(__('Display on Invoice'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('early_discount')
                    ->boolean()
                    ->label(__('Early Discount'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('Created By'))
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('company.name')
                    ->label(__('Company Name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('discount_days')
                    ->label(__('Discount Days'))
                    ->collapsible(),
                Tables\Grouping\Group::make('early_pay_discount')
                    ->label(__('Early Pay Discount'))
                    ->collapsible(),
                Tables\Grouping\Group::make('name')
                    ->label(__('Name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('display_on_invoice')
                    ->label(__('Display on Invoice'))
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('early_discount')
                    ->label(__('Early Discount'))
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('discount_percentage')
                    ->label(__('Discount Percentage'))
                    ->date()
                    ->collapsible(),
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


    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPaymentTerm::class,
            Pages\EditPaymentTerm::class,
            Pages\ManagePaymentDueTerm::class,
        ]);
    }

    public static function getRelations(): array
    {
        $relations = [
            RelationGroup::make('Due Terms', [
                RelationManagers\PaymentDueTermRelationManager::class,
            ])
                ->icon('heroicon-o-banknotes'),
        ];

        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index'             => Pages\ListPaymentTerms::route('/'),
            'create'            => Pages\CreatePaymentTerm::route('/create'),
            'view'              => Pages\ViewPaymentTerm::route('/{record}'),
            'edit'              => Pages\EditPaymentTerm::route('/{record}/edit'),
            'payment-due-terms' => Pages\ManagePaymentDueTerm::route('/{record}/payment-due-terms'),
        ];
    }
}
