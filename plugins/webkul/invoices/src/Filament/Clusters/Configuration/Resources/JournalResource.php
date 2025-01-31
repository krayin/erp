<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\JournalResource\Pages;
use Webkul\Invoice\Models\Journal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Invoice\Enums\CommunicationStandard;
use Webkul\Invoice\Enums\CommunicationType;
use Webkul\Invoice\Enums\JournalType;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Journal');
    }

    public static function getNavigationLabel(): string
    {
        return __('Journals');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Accounting');
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
                                        Forms\Components\Tabs\Tab::make('Journal Entries')
                                            ->schema([
                                                Forms\Components\Fieldset::make('Accounting Information')
                                                    ->schema([
                                                        Forms\Components\Group::make()
                                                            ->schema([
                                                                Forms\Components\Toggle::make('refund_order')
                                                                    ->hidden(function (Get $get) {
                                                                        return ! in_array($get('type'), [JournalType::SALE->value, JournalType::PURCHASE->value]);
                                                                    })
                                                                    ->label('Dedicated Credit Note Sequence'),

                                                                Forms\Components\Toggle::make('payment_order')
                                                                    ->hidden(function (Get $get) {
                                                                        return ! in_array($get('type'), [JournalType::BANK->value, JournalType::CASH->value, JournalType::CREDIT_CARD->value]);
                                                                    })
                                                                    ->label('Dedicated Payment Sequence'),

                                                                Forms\Components\TextInput::make('code')
                                                                    ->label('Short Code')
                                                                    ->placeholder('Enter the journal code'),
                                                                Forms\Components\Select::make('currency_id')
                                                                    ->label('Currency')
                                                                    ->relationship('currency', 'name')
                                                                    ->preload()
                                                                    ->searchable(),
                                                                Forms\Components\ColorPicker::make('color')
                                                                    ->label('Color'),
                                                            ])
                                                    ]),
                                                Forms\Components\Fieldset::make('Bank Account Number')
                                                    ->visible(function (Get $get) {
                                                        return $get('type') === JournalType::BANK->value;
                                                    })
                                                    ->schema([
                                                        Forms\Components\Group::make()
                                                            ->schema([
                                                                Forms\Components\Select::make('bank_account_id')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->relationship('bankAccount', 'account_number')
                                                                    ->hiddenLabel()
                                                            ])
                                                    ])
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Incoming Payments')
                                            ->visible(function (Get $get) {
                                                return in_array($get('type'), [
                                                    JournalType::BANK->value,
                                                    JournalType::CASH->value,
                                                    JournalType::BANK->value,
                                                    JournalType::CREDIT_CARD->value
                                                ]);
                                            })
                                            ->schema([
                                                Forms\Components\Textarea::make('relation_notes')
                                                    ->label('Relation Notes')
                                                    ->placeholder('Enter any relation details'),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Outgoing Payments')
                                            ->visible(function (Get $get) {
                                                return in_array($get('type'), [
                                                    JournalType::BANK->value,
                                                    JournalType::CASH->value,
                                                    JournalType::BANK->value,
                                                    JournalType::CREDIT_CARD->value
                                                ]);
                                            })
                                            ->schema([
                                                Forms\Components\Textarea::make('relation_notes')
                                                    ->label('Relation Notes')
                                                    ->placeholder('Enter any relation details'),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Advanced Settings')
                                            ->schema([
                                                Forms\Components\Fieldset::make('Control-Access')
                                                    ->schema([
                                                        Forms\Components\Group::make()
                                                            ->schema([
                                                                Forms\Components\Select::make('invoices_journal_accounts')
                                                                    ->relationship('allowedAccounts', 'name')
                                                                    ->multiple()
                                                                    ->preload()
                                                                    ->label('Allowed Accounts'),
                                                                Forms\Components\Toggle::make('auto_check_on_post')
                                                                    ->label('Auto Check on Post'),
                                                            ])
                                                    ]),
                                                Forms\Components\Fieldset::make('Payment Communications')
                                                    ->visible(fn(Get $get) => $get('type') === JournalType::SALE->value)
                                                    ->schema([
                                                        Forms\Components\Select::make('invoice_reference_type')
                                                            ->options(CommunicationType::options())
                                                            ->label('Communication Type'),
                                                        Forms\Components\Select::make('invoice_reference_model')
                                                            ->options(CommunicationStandard::options())
                                                            ->label('Communication Standard'),
                                                    ]),
                                            ]),
                                    ])
                                    ->persistTabInQueryString(),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make(__('General'))
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->required()
                                                    ->placeholder('Enter the name of the journal'),
                                                Forms\Components\Select::make('type')
                                                    ->label('Type')
                                                    ->options(JournalType::options())
                                                    ->required()
                                                    ->live()
                                                    ->placeholder('Select journal type'),
                                                Forms\Components\Select::make('company_id')
                                                    ->label('Company')
                                                    ->disabled()
                                                    ->relationship('company', 'name')
                                                    ->default(Auth::user()->default_company_id)
                                                    ->required()
                                            ]),
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
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'view' => Pages\ViewJournal::route('/{record}'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }
}
