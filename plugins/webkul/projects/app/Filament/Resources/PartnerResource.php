<?php

namespace Webkul\Project\Filament\Resources;

use Webkul\Partner\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Webkul\Partner\Enums\AccountType;

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
                        AccountType::COMPANY->value => 'Company',
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
                Forms\Components\Group::make()
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
                Forms\Components\TextInput::make('avatar')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('job_title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tax_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile')
                    ->maxLength(255),
                Forms\Components\TextInput::make('color')
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_registry')
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference')
                    ->maxLength(255),
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name'),
                Forms\Components\Select::make('creator_id')
                    ->relationship('creator', 'name'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('title_id')
                    ->relationship('title', 'name'),
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name'),
                Forms\Components\Select::make('industry_id')
                    ->relationship('industry', 'name'),
            ])
            ->columns(2);
    }
}
