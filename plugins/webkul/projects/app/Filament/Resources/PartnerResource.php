<?php

namespace Webkul\Project\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Webkul\Partner\Enums\AccountType;
use Webkul\Partner\Models\Partner;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Radio::make('account_type')
                    ->hiddenLabel()
                    ->inline()
                    ->columnSpan(2)
                    ->options([
                        AccountType::INDIVIDUAL->value => 'Individual',
                        AccountType::COMPANY->value    => 'Company',
                    ])
                    ->default(AccountType::INDIVIDUAL->value)
                    ->live(),
                Forms\Components\TextInput::make('name')
                    ->hiddenLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2)
                    ->placeholder(fn (Forms\Get $get): string => $get('account_type') === AccountType::INDIVIDUAL->value ? 'Jhon Doe' : 'ACME Corp')
                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->visible(fn (Forms\Get $get): bool => $get('account_type') === AccountType::INDIVIDUAL->value)
                    ->searchable()
                    ->preload()
                    ->columnSpan(2),
                Forms\Components\Group::make()
                    ->label('Avatar')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageEditor()
                            ->imagePreviewHeight('140')
                            ->panelAspectRatio('4:1')
                            ->panelLayout('integrated')
                            ->directory('employees/avatar')
                            ->visibility('private'),
                    ])
                    ->columnSpan(2),
                Forms\Components\TextInput::make('tax_id')
                    ->label('Tax ID')
                    ->placeholder('e.g. 29ABCDE1234F1Z5')
                    ->maxLength(255),
                Forms\Components\TextInput::make('job_title')
                    ->label('Job Title')
                    ->placeholder('e.g. CEO')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile')
                    ->label('Mobile')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('website')
                    ->label('Website')
                    ->maxLength(255)
                    ->url(),
                Forms\Components\Select::make('title_id')
                    ->label('Title')
                    ->relationship('title', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->unique('partners_titles'),
                        Forms\Components\TextInput::make('short_name')
                            ->label('Short Name')
                            ->required()
                            ->unique('partners_titles'),
                        Forms\Components\Hidden::make('creator_id')
                            ->default(Auth::user()->id),
                    ]),
                Forms\Components\Select::make('tags')
                    ->label('Tags')
                    ->relationship(name: 'tags', titleAttribute: 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->unique('partners_tags'),
                        Forms\Components\ColorPicker::make('color'),
                    ]),
                Forms\Components\Tabs::make('Employee Information')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Contacts')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                            ]),

                        Forms\Components\Tabs\Tab::make('Addresses')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                            ]),

                        Forms\Components\Tabs\Tab::make('Sales and Purchases')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Fieldset::make('Sales')
                                    ->schema([
                                        Forms\Components\Select::make('user_id')
                                            ->label('Responsible')
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'This is internal salesperson responsible for this customer'),
                                    ])
                                    ->columns(1),

                                Forms\Components\Fieldset::make('Others')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_registry')
                                            ->label('Company Id')
                                            ->maxLength(255)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'The registry number of the company. Use it if it is different from the Tax ID. It must be unique across all partners of a same country'),
                                        Forms\Components\TextInput::make('reference')
                                            ->label('Reference')
                                            ->maxLength(255),
                                        Forms\Components\Select::make('industry_id')
                                            ->label('Industry')
                                            ->relationship('industry', 'name'),
                                    ])
                                    ->columns(2),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(2),
            ])
            ->columns(2);
    }
}
