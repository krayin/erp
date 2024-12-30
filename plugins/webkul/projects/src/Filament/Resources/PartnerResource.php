<?php

namespace Webkul\Project\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
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
                Forms\Components\Section::make(__('projects::filament/resources/partner.form.sections.general.title'))
                    ->schema([
                        Forms\Components\Radio::make('account_type')
                            ->hiddenLabel()
                            ->inline()
                            ->columnSpan(2)
                            ->options([
                                AccountType::INDIVIDUAL->value => AccountType::options()[AccountType::INDIVIDUAL->value],
                                AccountType::COMPANY->value    => AccountType::options()[AccountType::COMPANY->value],
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
                        Forms\Components\Select::make('parent_id')
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.company'))
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('account_type', AccountType::COMPANY->value),
                            )
                            ->visible(fn (Forms\Get $get): bool => $get('account_type') === AccountType::INDIVIDUAL->value)
                            ->searchable()
                            ->preload()
                            ->columnSpan(2)
                            ->createOptionForm(fn (Form $form): Form => self::form($form))
                            ->editOptionForm(fn (Form $form): Form => self::form($form))
                            ->createOptionAction(function (Action $action) {
                                $action
                                    ->fillForm(function (array $arguments): array {
                                        return [
                                            'account_type' => AccountType::COMPANY->value,
                                        ];
                                    })
                                    ->mutateFormDataUsing(function (array $data) {
                                        $data['account_type'] = AccountType::COMPANY->value;

                                        return $data;
                                    });
                            }),
                        Forms\Components\Group::make()
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.avatar'))
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
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.tax-id'))
                            ->placeholder('e.g. 29ABCDE1234F1Z5')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('job_title')
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.job-title'))
                            ->placeholder('e.g. CEO')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.phone'))
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('mobile')
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.mobile'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.email'))
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('website')
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.website'))
                            ->maxLength(255)
                            ->url(),
                        Forms\Components\Select::make('title_id')
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.title'))
                            ->relationship('title', 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('projects::filament/resources/partner.form.sections.general.fields.name'))
                                    ->required()
                                    ->unique('partners_titles'),
                                Forms\Components\TextInput::make('short_name')
                                    ->label(__('projects::filament/resources/partner.form.sections.general.fields.short-name'))
                                    ->label('Short Name')
                                    ->required()
                                    ->unique('partners_titles'),
                                Forms\Components\Hidden::make('creator_id')
                                    ->default(Auth::user()->id),
                            ]),
                        Forms\Components\Select::make('tags')
                            ->label(__('projects::filament/resources/partner.form.sections.general.fields.tags'))
                            ->relationship(name: 'tags', titleAttribute: 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('projects::filament/resources/partner.form.sections.general.fields.name'))
                                    ->required()
                                    ->unique('partners_tags'),
                            ]),
                    ]),

                Forms\Components\Tabs::make('tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('projects::filament/resources/partner.form.tabs.sales-purchase.title'))
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Fieldset::make('Sales')
                                    ->schema([
                                        Forms\Components\Select::make('user_id')
                                            ->label(__('projects::filament/resources/partner.form.tabs.sales-purchase.fields.responsible'))
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('projects::filament/resources/partner.form.tabs.sales-purchase.fields.responsible-hint-text')),
                                    ])
                                    ->columns(1),

                                Forms\Components\Fieldset::make('Others')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_registry')
                                            ->label(__('projects::filament/resources/partner.form.tabs.sales-purchase.fields.company-id'))
                                            ->maxLength(255)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('projects::filament/resources/partner.form.tabs.sales-purchase.fields.company-id-hint-text')),
                                        Forms\Components\TextInput::make('reference')
                                            ->label(__('projects::filament/resources/partner.form.tabs.sales-purchase.fields.reference'))
                                            ->maxLength(255),
                                        Forms\Components\Select::make('industry_id')
                                            ->label(__('projects::filament/resources/partner.form.tabs.sales-purchase.fields.industry'))
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
