<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources;

use Webkul\Sale\Filament\Clusters\Configuration;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sale\Models\OrderTemplate;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Webkul\Sale\Enums\OrderDisplayType;

class QuotationTemplateResource extends Resource
{
    protected static ?string $model = OrderTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Quotation Template');
    }

    public static function getNavigationLabel(): string
    {
        return __('Quotation Templates');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales Orders');
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
            __('name')    => $record->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Tabs::make()
                                    ->tabs([
                                        Forms\Components\Tabs\Tab::make(__('Products'))
                                            ->schema([
                                                static::getProductRepeater(),
                                                static::getSectionRepeater(),
                                                static::getNoteRepeater(),
                                            ]),
                                        Forms\Components\Tabs\Tab::make(__('Terms & Conditions'))
                                            ->schema([
                                                Forms\Components\RichEditor::make('note')
                                                    ->hiddenLabel()
                                            ]),
                                    ])
                                    ->persistTabInQueryString(),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make('General Information')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->required(),
                                                Forms\Components\TextInput::make('number_of_days')
                                                    ->label('Quotation Validity')
                                                    ->default(0)
                                                    ->required(),
                                                Forms\Components\Select::make('journal_id')
                                                    ->relationship('journal', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->label('Sales Journal')
                                                    ->required()
                                            ])->columns(1)
                                    ]),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make('Signature & Payment')
                                            ->schema([
                                                Forms\Components\Toggle::make('require_signature')
                                                    ->label('Online Signature'),
                                                Forms\Components\Toggle::make('require_payment')
                                                    ->live()
                                                    ->label('Online Payment'),
                                                Forms\Components\TextInput::make('prepayment_percentage')
                                                    ->prefix('of')
                                                    ->suffix('%')
                                                    ->visible(fn(Get $get) => $get('require_payment') === true),
                                            ])->columns(1)
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->placeholder('-')
                    ->sortable()
                    ->searchable()
                    ->label(__('Created By')),
                Tables\Columns\TextColumn::make('company.name')
                    ->placeholder('-')
                    ->sortable()
                    ->searchable()
                    ->label(__('Company')),
                Tables\Columns\TextColumn::make('name')
                    ->placeholder('-')
                    ->sortable()
                    ->searchable()
                    ->label(__('Name')),
                Tables\Columns\TextColumn::make('number_of_days')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-')
                    ->label(__('Quotation Validity')),
                Tables\Columns\TextColumn::make('journal.name')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-')
                    ->label(__('Sales Journal')),
                Tables\Columns\IconColumn::make('require_signature')
                    ->placeholder('-')
                    ->boolean()
                    ->label(__('Online Signature')),
                Tables\Columns\IconColumn::make('require_payment')
                    ->placeholder('-')
                    ->boolean()
                    ->label(__('Online Payment')),
                Tables\Columns\TextColumn::make('prepayment_percentage')
                    ->placeholder('-')
                    ->label(__('Prepayment Percentage')),
            ])
            ->filtersFormColumns(2)
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('createdBy.name')
                            ->label(__('Created By'))
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Created By'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company.name')
                            ->label(__('Company'))
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Company'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('name')
                            ->label(__('Name'))
                            ->icon('heroicon-o-building-office-2')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('Name'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('Created At')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('Updated At')),
                    ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('company.name')
                    ->label(__('Company'))
                    ->collapsible(),
                Tables\Grouping\Group::make('name')
                    ->label(__('Name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('journal.name')
                    ->label(__('Journal'))
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListQuotationTemplates::route('/'),
            'create' => Pages\CreateQuotationTemplate::route('/create'),
            'view'   => Pages\ViewQuotationTemplate::route('/{record}'),
            'edit'   => Pages\EditQuotationTemplate::route('/{record}/edit'),
        ];
    }

    public static function getProductRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('products')
            ->relationship('products')
            ->hiddenLabel()
            ->reorderable()
            ->collapsible()
            ->cloneable()
            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ->deleteAction(
                fn(Action $action) => $action->requiresConfirmation(),
            )
            ->extraItemActions([
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->action(function (
                        array $arguments,
                        $livewire
                    ): void {
                        $recordId = explode('-', $arguments['item'])[1];

                        $redirectUrl = OrderTemplateProductResource::getUrl('edit', ['record' => $recordId]);

                        $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
                    }),
            ])
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label('Product')
                                    ->required(),
                                Forms\Components\TextInput::make('name')
                                    ->live(onBlur: true)
                                    ->label('Name'),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->label('Quantity'),
                            ]),
                    ])->columns(2)
            ]);
    }

    public static function getSectionRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('sections')
            ->relationship('sections')
            ->hiddenLabel()
            ->reorderable()
            ->collapsible()
            ->cloneable()
            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ->deleteAction(
                fn(Action $action) => $action->requiresConfirmation(),
            )
            ->extraItemActions([
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->action(function (
                        array $arguments,
                        $livewire
                    ): void {
                        $recordId = explode('-', $arguments['item'])[1];

                        $redirectUrl = OrderTemplateProductResource::getUrl('edit', ['record' => $recordId]);

                        $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
                    }),
            ])
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->live(onBlur: true)
                            ->label('Name'),
                        Forms\Components\Hidden::make('quantity')
                            ->required()
                            ->default(0),
                        Forms\Components\Hidden::make('display_type')
                            ->required()
                            ->default(OrderDisplayType::SECTION->value)
                    ]),
            ]);
    }

    public static function getNoteRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('notes')
            ->relationship('notes')
            ->hiddenLabel()
            ->reorderable()
            ->collapsible()
            ->cloneable()
            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ->deleteAction(
                fn(Action $action) => $action->requiresConfirmation(),
            )
            ->extraItemActions([
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->action(function (
                        array $arguments,
                        $livewire
                    ): void {
                        $recordId = explode('-', $arguments['item'])[1];

                        $redirectUrl = OrderTemplateProductResource::getUrl('edit', ['record' => $recordId]);

                        $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
                    }),
            ])
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->live(onBlur: true)
                            ->label('Name'),
                        Forms\Components\Hidden::make('quantity')
                            ->required()
                            ->default(0),
                        Forms\Components\Hidden::make('display_type')
                            ->required()
                            ->default(OrderDisplayType::NOTE->value)
                    ]),
            ]);
    }
}
