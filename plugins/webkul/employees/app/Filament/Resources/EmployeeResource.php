<?php

namespace Webkul\Employee\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Enums\Gender;
use Webkul\Employee\Enums\MaritalStatus;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\DepartureReasonResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmployeeCategoryResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\WorkLocationResource;
use Webkul\Employee\Filament\Resources\EmployeeResource\Pages;
use Webkul\Employee\Filament\Resources\EmployeeResource\RelationManagers;
use Webkul\Employee\Models\Calendar;
use Webkul\Employee\Models\Employee;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Security\Filament\Resources\CompanyResource;
use Webkul\Security\Filament\Resources\UserResource;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

class EmployeeResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Employees';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;'])
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('job_title')
                                            ->label('Job Title')
                                            ->maxLength(255)
                                            ->columnSpan(1),

                                    ]),
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
                                    ]),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('work_email')
                                    ->label('Work Email')
                                    ->suffixAction(
                                        Action::make('open_mailbox')
                                            ->icon('heroicon-o-envelope')
                                            ->color('gray')
                                            ->action(function (Set $set, ?string $state) {
                                                if ($state && filter_var($state, FILTER_VALIDATE_EMAIL)) {
                                                    $set('work_email', $state);
                                                }
                                            })
                                            ->url(fn(?string $state) => $state ? "mailto:{$state}" : '#')
                                    )
                                    ->email(),
                                Forms\Components\Select::make('department_id')
                                    ->label('Department')
                                    ->relationship(name: 'department', titleAttribute: 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(fn(Form $form) => DepartmentResource::form($form))
                                    ->editOptionForm(fn(Form $form) => DepartmentResource::form($form)),
                                Forms\Components\TextInput::make('mobile_phone')
                                    ->label('Work Mobile')
                                    ->suffixAction(
                                        Action::make('open_mobile_phone')
                                            ->icon('heroicon-o-phone')
                                            ->color('blue')
                                            ->action(function (Set $set, $state) {
                                                $set('mobile_phone', $state);
                                            })
                                            ->url(fn(?string $state) => $state ? "tel:{$state}" : '#')
                                    )
                                    ->tel(),
                                Forms\Components\Select::make('job_id')
                                    ->relationship('job', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label('Job Position')
                                    ->createOptionForm(fn(Form $form) => JobPositionResource::form($form))
                                    ->editOptionForm(fn(Form $form) => JobPositionResource::form($form)),
                                Forms\Components\TextInput::make('work_phone')
                                    ->label('Work Phone')
                                    ->suffixAction(
                                        Action::make('open_work_phone')
                                            ->icon('heroicon-o-phone')
                                            ->color('blue')
                                            ->action(function (Set $set, $state) {
                                                $set('work_phone', $state);
                                            })
                                            ->url(fn(?string $state) => $state ? "tel:{$state}" : '#')
                                    )
                                    ->tel(),
                                Forms\Components\Select::make('parent_id')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->suffixIcon('heroicon-o-user')
                                    ->label('Manager'),
                                Forms\Components\Select::make('employees_employee_categories')
                                    ->multiple()
                                    ->relationship('categories', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label('Employee Tags')
                                    ->createOptionForm(fn(Form $form) => EmployeeCategoryResource::form($form)),
                                Forms\Components\Select::make('coach_id')
                                    ->searchable()
                                    ->preload()
                                    ->relationship('coach', 'name')
                                    ->label('Coach'),
                            ])
                            ->columns(2),

                    ])
                    ->columns(1),
                Forms\Components\Tabs::make('Employee Information')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Work Information')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Fieldset::make('Location')
                                                    ->schema([
                                                        Forms\Components\Select::make('address_id')
                                                            ->options(fn() => Company::pluck('name', 'id'))
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->suffixIcon('heroicon-o-map-pin')
                                                            ->label('Work Address'),
                                                        Forms\Components\Placeholder::make('address')
                                                            ->hiddenLabel()
                                                            ->content(function (Get $get) {
                                                                if ($get('address_id')) {
                                                                    $address = Company::find($get('address_id'))?->address;

                                                                    if ($address) {
                                                                        return implode(' ', array_filter([
                                                                            "{$address->street1}, {$address->street2}",
                                                                            "{$address->city}, {$address->state->name} - {$address->zip}",
                                                                            $address->country->name,
                                                                        ]));
                                                                    }
                                                                }

                                                                return null;
                                                            })
                                                            ->visible(fn(Get $get) => $get('address_id') != null),
                                                        Forms\Components\Select::make('work_location_id')
                                                            ->relationship('workLocation', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Work Location')
                                                            ->prefixIcon('heroicon-o-map-pin')
                                                            ->createOptionForm(fn(Form $form) => WorkLocationResource::form($form))
                                                            ->editOptionForm(fn(Form $form) => WorkLocationResource::form($form)),
                                                    ])->columns(1),
                                                Forms\Components\Fieldset::make('Approvers')
                                                    ->schema([
                                                        Forms\Components\Select::make('leave_manager_id')
                                                            ->options(fn() => User::pluck('name', 'id'))
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->suffixIcon('heroicon-o-clock')
                                                            ->label('Time Off'),
                                                    ])->columns(1),
                                                Forms\Components\Fieldset::make('Schedule')
                                                    ->schema([
                                                        Forms\Components\Select::make('calendar_id')
                                                            ->options(fn() => Calendar::pluck('name', 'id'))
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->suffixIcon('heroicon-o-clock')
                                                            ->label('Working Hours'),
                                                        Forms\Components\Select::make('time_zone')
                                                            ->label('Time Zone')
                                                            ->options(function () {
                                                                return collect(timezone_identifiers_list())->mapWithKeys(function ($timezone) {
                                                                    return [$timezone => $timezone];
                                                                });
                                                            })
                                                            ->default(date_default_timezone_get())
                                                            ->preload()
                                                            ->suffixIcon('heroicon-o-clock')
                                                            ->searchable()
                                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: 'Specify the time zone for this work schedule'),
                                                    ])->columns(1),
                                            ])
                                            ->columnSpan(['lg' => 2]),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\Fieldset::make('Organizational Details')
                                                            ->schema([
                                                                Forms\Components\Select::make('company_id')
                                                                    ->relationship('company', 'name')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->prefixIcon('heroicon-o-building-office')
                                                                    ->label('Company')
                                                                    ->createOptionForm(fn(Form $form) => CompanyResource::form($form))
                                                                    ->editOptionForm(fn(Form $form) => CompanyResource::form($form)),
                                                                Forms\Components\ColorPicker::make('color')
                                                                    ->label('Color'),
                                                            ])->columns(1),
                                                    ])
                                                    ->columnSpan(['lg' => 1]),
                                            ])
                                            ->columnSpan(['lg' => 1]),
                                    ])
                                    ->columns(3),
                            ]),
                        Forms\Components\Tabs\Tab::make('Private Information')
                            ->icon('heroicon-o-lock-closed')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\Fieldset::make('Private Contact')
                                                            ->schema([
                                                                Forms\Components\Select::make('private_country_id')
                                                                    ->relationship('privateCountry', 'name')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->label('Private Country')
                                                                    ->afterStateUpdated(fn(Set $set) => $set('private_state_id', null))
                                                                    ->createOptionForm([
                                                                        Forms\Components\Select::make('currency_id')
                                                                            ->options(fn() => Currency::pluck('full_name', 'id'))
                                                                            ->searchable()
                                                                            ->preload()
                                                                            ->label('Currency Name')
                                                                            ->required(),
                                                                        Forms\Components\TextInput::make('phone_code')
                                                                            ->label('Phone Code')
                                                                            ->required(),
                                                                        Forms\Components\TextInput::make('code')
                                                                            ->label('Code')
                                                                            ->required()
                                                                            ->rules('max:2'),
                                                                        Forms\Components\TextInput::make('name')
                                                                            ->label('Country Name')
                                                                            ->required(),
                                                                        Forms\Components\Toggle::make('state_required')
                                                                            ->label('State Required')
                                                                            ->required(),
                                                                        Forms\Components\Toggle::make('zip_required')
                                                                            ->label('Zip Required')
                                                                            ->required(),
                                                                    ])
                                                                    ->createOptionAction(
                                                                        fn(Action $action) => $action
                                                                            ->modalHeading('Create Country')
                                                                            ->modalSubmitActionLabel('Create Country')
                                                                            ->modalWidth('lg')
                                                                    )
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->live(),
                                                                Forms\Components\Select::make('private_state_id')
                                                                    ->relationship('privateState', 'name')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->label('Private State')
                                                                    ->createOptionForm([
                                                                        Forms\Components\TextInput::make('name')
                                                                            ->label('Name')
                                                                            ->required()
                                                                            ->maxLength(255),
                                                                        Forms\Components\TextInput::make('code')
                                                                            ->label('Code')
                                                                            ->required()
                                                                            ->maxLength(255),
                                                                    ])
                                                                    ->createOptionAction(
                                                                        fn(Action $action) => $action
                                                                            ->modalHeading('Create State')
                                                                            ->modalSubmitActionLabel('Create State')
                                                                            ->modalWidth('lg')
                                                                    ),
                                                                Forms\Components\TextInput::make('private_street1')
                                                                    ->label('Street Address'),
                                                                Forms\Components\TextInput::make('private_street2')
                                                                    ->label('Private Street 2'),
                                                                Forms\Components\TextInput::make('private_city')
                                                                    ->label('City'),
                                                                Forms\Components\TextInput::make('private_zip')
                                                                    ->label('Postal Code'),
                                                                Forms\Components\TextInput::make('private_phone')
                                                                    ->label('Private Phone')
                                                                    ->suffixAction(
                                                                        Action::make('open_private_phone')
                                                                            ->icon('heroicon-o-phone')
                                                                            ->color('blue')
                                                                            ->action(function (Set $set, $state) {
                                                                                $set('private_phone', $state);
                                                                            })
                                                                            ->url(fn(?string $state) => $state ? "tel:{$state}" : '#')
                                                                    )
                                                                    ->tel(),
                                                                Forms\Components\Select::make('bank_account_id')
                                                                    ->relationship('bankAccount', 'account_number')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->createOptionForm([
                                                                        Forms\Components\Group::make()
                                                                            ->schema([
                                                                                Forms\Components\TextInput::make('account_number')
                                                                                    ->label('Account Number')
                                                                                    ->required(),
                                                                                Forms\Components\Hidden::make('account_holder_name')
                                                                                    ->label('Account Number')
                                                                                    ->default(function (Get $get, $livewire) {
                                                                                        return $livewire->record->user?->name ?? $get('name');
                                                                                    })
                                                                                    ->required(),
                                                                                Forms\Components\Hidden::make('partner_id')
                                                                                    ->label('Account Number')
                                                                                    ->default(function (Get $get, $livewire) {
                                                                                        return $livewire->record->partner?->id ?? $get('name');
                                                                                    })
                                                                                    ->required(),
                                                                                Forms\Components\Hidden::make('creator_id')
                                                                                    ->default(fn() => Auth::user()->id),
                                                                                Forms\Components\Select::make('bank_id')
                                                                                    ->relationship('bank', 'name')
                                                                                    ->label('Bank')
                                                                                    ->searchable()
                                                                                    ->preload()
                                                                                    ->createOptionForm(static::getBankCreateSchema())
                                                                                    ->editOptionForm(static::getBankCreateSchema())
                                                                                    ->createOptionAction(fn(Action $action) => $action->modalHeading('Create Bank'))
                                                                                    ->live()
                                                                                    ->required(),
                                                                                Forms\Components\Toggle::make('is_active')
                                                                                    ->label('Status')
                                                                                    ->default(true)
                                                                                    ->inline(false),
                                                                                Forms\Components\Toggle::make('can_send_money')
                                                                                    ->label('Send Money')
                                                                                    ->default(true)
                                                                                    ->inline(false),

                                                                            ])->columns(2),
                                                                    ])
                                                                    ->createOptionAction(
                                                                        fn(Action $action) => $action
                                                                            ->modalHeading('Create Bank Account')
                                                                            ->modalSubmitActionLabel('Create Bank Account')
                                                                    )
                                                                    ->disabledOn('create')
                                                                    ->label('Bank Account'),
                                                                Forms\Components\TextInput::make('private_email')
                                                                    ->label('Private Email')
                                                                    ->suffixAction(
                                                                        Action::make('open_private_email')
                                                                            ->icon('heroicon-o-envelope')
                                                                            ->color('blue')
                                                                            ->action(function (Set $set, $state) {
                                                                                if (filter_var($state, FILTER_VALIDATE_EMAIL)) {
                                                                                    $set('private_email', $state);
                                                                                }
                                                                            })
                                                                            ->url(fn(?string $state) => $state ? "mailto:{$state}" : '#')
                                                                    )
                                                                    ->email(),
                                                                Forms\Components\TextInput::make('private_car_plate')
                                                                    ->label('Private Car Plate'),
                                                                Forms\Components\TextInput::make('distance_home_work')
                                                                    ->label('Distance Home to Work')
                                                                    ->numeric()
                                                                    ->default(0)
                                                                    ->suffix('km'),
                                                                Forms\Components\TextInput::make('km_home_work')
                                                                    ->label('KM Home to Work')
                                                                    ->numeric()
                                                                    ->default(0)
                                                                    ->suffix('km'),
                                                                Forms\Components\TextInput::make('distance_home_work_unit')
                                                                    ->default(0)
                                                                    ->label('Distance Unit'),
                                                            ])->columns(2),
                                                        Forms\Components\Group::make()
                                                            ->schema([
                                                                Forms\Components\Fieldset::make('Emergency Contact')
                                                                    ->schema([
                                                                        Forms\Components\TextInput::make('emergency_contact')
                                                                            ->label('Contact Name'),
                                                                        Forms\Components\TextInput::make('emergency_phone')
                                                                            ->label('Contact Phone')
                                                                            ->suffixAction(
                                                                                Action::make('open_emergency_phone')
                                                                                    ->icon('heroicon-o-phone')
                                                                                    ->color('blue')
                                                                                    ->action(function (Set $set, $state) {
                                                                                        $set('emergency_phone', $state);
                                                                                    })
                                                                                    ->url(fn(?string $state) => $state ? "tel:{$state}" : '#')
                                                                            )
                                                                            ->tel(),
                                                                    ])->columns(2),
                                                            ])
                                                            ->columnSpan(['lg' => 1]),
                                                        Forms\Components\Fieldset::make('Family Status')
                                                            ->schema([
                                                                Forms\Components\Select::make('marital')
                                                                    ->label('Marital Status')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->default(MaritalStatus::Single->value)
                                                                    ->options(MaritalStatus::options())
                                                                    ->live(),
                                                                Forms\Components\TextInput::make('spouse_complete_name')
                                                                    ->label('Spouse Name')
                                                                    ->hidden(fn(Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->dehydrated(fn(Get $get) => $get('marital') !== MaritalStatus::Single->value)
                                                                    ->required(fn(Get $get) => $get('marital') !== MaritalStatus::Single->value),
                                                                Forms\Components\DatePicker::make('spouse_birthdate')
                                                                    ->label('Spouse Birthdate')
                                                                    ->native(false)
                                                                    ->suffixIcon('heroicon-o-calendar')
                                                                    ->disabled(fn(Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->hidden(fn(Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->dehydrated(fn(Get $get) => $get('marital') !== MaritalStatus::Single->value),
                                                                Forms\Components\TextInput::make('children')
                                                                    ->label('Number of Children')
                                                                    ->numeric()
                                                                    ->minValue(0)
                                                                    ->disabled(fn(Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->hidden(fn(Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->dehydrated(fn(Get $get) => $get('marital') !== MaritalStatus::Single->value),
                                                            ])->columns(2),
                                                        Forms\Components\Fieldset::make('Education')
                                                            ->schema([
                                                                Forms\Components\Select::make('certificate')
                                                                    ->options([
                                                                        'graduate' => 'Graduate',
                                                                        'bachelor' => 'Bachelor',
                                                                        'master'   => 'Master',
                                                                        'doctor'   => 'Doctor',
                                                                        'other'    => 'Other',
                                                                    ])
                                                                    ->label('Certificate Level'),
                                                                Forms\Components\TextInput::make('study_field')
                                                                    ->label('Field of Study'),
                                                                Forms\Components\TextInput::make('study_school')
                                                                    ->label('School'),
                                                            ])->columns(1),

                                                    ]),
                                            ])
                                            ->columnSpan(['lg' => 2]),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Fieldset::make('Citizenship')
                                                    ->schema([
                                                        Forms\Components\Select::make('country_id')
                                                            ->label('Country')
                                                            ->relationship(name: 'country', titleAttribute: 'name')
                                                            ->afterStateUpdated(fn(Set $set) => $set('state_id', null))
                                                            ->createOptionForm([
                                                                Forms\Components\Select::make('currency_id')
                                                                    ->options(fn() => Currency::pluck('full_name', 'id'))
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->label('Currency Name')
                                                                    ->required(),
                                                                Forms\Components\TextInput::make('phone_code')
                                                                    ->label('Phone Code')
                                                                    ->required(),
                                                                Forms\Components\TextInput::make('code')
                                                                    ->label('Code')
                                                                    ->required()
                                                                    ->rules('max:2'),
                                                                Forms\Components\TextInput::make('name')
                                                                    ->label('Country Name')
                                                                    ->required(),
                                                                Forms\Components\Toggle::make('state_required')
                                                                    ->label('State Required')
                                                                    ->required(),
                                                                Forms\Components\Toggle::make('zip_required')
                                                                    ->label('Zip Required')
                                                                    ->required(),
                                                            ])
                                                            ->createOptionAction(
                                                                fn(Action $action) => $action
                                                                    ->modalHeading('Create Country')
                                                                    ->modalSubmitActionLabel('Create Country')
                                                                    ->modalWidth('lg')
                                                            )
                                                            ->searchable()
                                                            ->preload()
                                                            ->live(),
                                                        Forms\Components\Select::make('state_id')
                                                            ->relationship('state', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('State')
                                                            ->createOptionForm([
                                                                Forms\Components\TextInput::make('name')
                                                                    ->label('Name')
                                                                    ->required()
                                                                    ->maxLength(255),
                                                                Forms\Components\TextInput::make('code')
                                                                    ->label('Code')
                                                                    ->required()
                                                                    ->maxLength(255),
                                                            ])
                                                            ->createOptionAction(
                                                                fn(Action $action) => $action
                                                                    ->modalHeading('Create State')
                                                                    ->modalSubmitActionLabel('Create State')
                                                                    ->modalWidth('lg')
                                                            ),
                                                        Forms\Components\TextInput::make('identification_id')
                                                            ->label('Identification No'),
                                                        Forms\Components\TextInput::make('ssnid')
                                                            ->label('SSN No'),
                                                        Forms\Components\TextInput::make('sinid')
                                                            ->label('SIN ID'),
                                                        Forms\Components\TextInput::make('passport_id')
                                                            ->label('Passport No'),
                                                        Forms\Components\Select::make('gender')
                                                            ->label('Gender')
                                                            ->searchable()
                                                            ->preload()
                                                            ->options(Gender::options()),
                                                        Forms\Components\DatePicker::make('birthday')
                                                            ->label('Date of Birth')
                                                            ->suffixIcon('heroicon-o-calendar')
                                                            ->native(false)
                                                            ->maxDate(now()),
                                                        Forms\Components\Select::make('country_of_birth')
                                                            ->relationship('countryOfBirth', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Country of Birth'),

                                                    ])->columns(1),
                                                Forms\Components\Fieldset::make('Work Permit')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('visa_no')
                                                            ->label('Visa Number'),
                                                        Forms\Components\TextInput::make('permit_no')
                                                            ->label('Work Permit No'),
                                                        Forms\Components\DatePicker::make('visa_expire')
                                                            ->label('Visa Expiration Date')
                                                            ->native(false),
                                                        Forms\Components\DatePicker::make('work_permit_expiration_date')
                                                            ->label('Work Permit Expiration Date')
                                                            ->native(false),
                                                        Forms\Components\FileUpload::make('work_permit')
                                                            ->label('Work Permit')
                                                            ->panelAspectRatio('4:1')
                                                            ->panelLayout('integrated')
                                                            ->directory('employees/work-permit')
                                                            ->visibility('private'),
                                                    ])->columns(1),
                                            ])
                                            ->columnSpan(['lg' => 1]),
                                    ])
                                    ->columns(3),
                            ]),
                        Forms\Components\Tabs\Tab::make('Settings')
                            ->icon('heroicon-o-cog-8-tooth')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Fieldset::make('Employment Status')
                                                    ->schema([
                                                        Forms\Components\Toggle::make('is_active')
                                                            ->label('Active Employee')
                                                            ->default(true)
                                                            ->inline(false),
                                                        Forms\Components\Toggle::make('is_flexible')
                                                            ->label('Flexible Work Arrangement')
                                                            ->inline(false),
                                                        Forms\Components\Toggle::make('is_fully_flexible')
                                                            ->label('Fully Flexible Schedule')
                                                            ->inline(false),
                                                        Forms\Components\Toggle::make('work_permit_scheduled_activity')
                                                            ->label('Work Permit Scheduled Activity'),
                                                        Forms\Components\Select::make('user_id')
                                                            ->relationship('user', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->suffixIcon('heroicon-o-user')
                                                            ->label('Related User')
                                                            ->createOptionForm(fn(Form $form) => UserResource::form($form))
                                                            ->editOptionForm(fn(Form $form) => UserResource::form($form))
                                                            ->createOptionAction(
                                                                fn(Action $action) => $action
                                                                    ->modalHeading('Create User')
                                                                    ->modalSubmitActionLabel('Create User')
                                                                    ->modalWidth(MaxWidth::MaxContent)
                                                                    ->action(function (array $data, Livewire $component) {
                                                                        $user = User::create($data);

                                                                        $partner = $user->partner()->create([
                                                                            'creator_id' => Auth::user()->id,
                                                                            'user_id'    => $user->id,
                                                                            'company_id' => $data['default_company_id'] ?? null,
                                                                            'avatar'     => $data['avatar'] ?? null,
                                                                            ...$data,
                                                                        ]);

                                                                        $user->update([
                                                                            'partner_id' => $partner->id,
                                                                        ]);

                                                                        $component->state($user->id);

                                                                        return $user;
                                                                    })
                                                            ),
                                                        Forms\Components\Select::make('departure_reason_id')
                                                            ->relationship('departureReason', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->label('Departure Reason')
                                                            ->createOptionForm(fn(Form $form) => DepartureReasonResource::form($form))
                                                            ->editOptionForm(fn(Form $form) => DepartureReasonResource::form($form)),
                                                        Forms\Components\DatePicker::make('departure_date')
                                                            ->label('Departure Date')
                                                            ->native(false)
                                                            ->hidden(fn(Get $get) => $get('departure_reason_id') === null)
                                                            ->disabled(fn(Get $get) => $get('departure_reason_id') === null)
                                                            ->required(fn(Get $get) => $get('departure_reason_id') !== null),
                                                        Forms\Components\Textarea::make('departure_description')
                                                            ->label('Departure Description')
                                                            ->hidden(fn(Get $get) => $get('departure_reason_id') === null)
                                                            ->disabled(fn(Get $get) => $get('departure_reason_id') === null)
                                                            ->required(fn(Get $get) => $get('departure_reason_id') !== null),
                                                    ])->columns(2),
                                                Forms\Components\Fieldset::make('Additional Information')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('lang')
                                                            ->label('Primary Language'),

                                                        Forms\Components\Textarea::make('additional_note')
                                                            ->label('Additional Notes')
                                                            ->rows(3),
                                                        Forms\Components\Textarea::make('notes')
                                                            ->label('Notes'),
                                                        ...static::getCustomFormFields(),
                                                    ])->columns(2),
                                            ])
                                            ->columnSpan(['lg' => 2]),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Fieldset::make('Attendance/Point of Sale')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('barcode')
                                                            ->label('Badge ID')
                                                            ->prefixIcon('heroicon-o-qr-code')
                                                            ->suffixAction(
                                                                Action::make('generate_bar_code')
                                                                    ->icon('heroicon-o-plus-circle')
                                                                    ->color('gray')
                                                                    ->action(function (Set $set) {
                                                                        $barcode = strtoupper(bin2hex(random_bytes(4)));

                                                                        $set('barcode', $barcode);
                                                                    })
                                                            ),
                                                        Forms\Components\TextInput::make('pin')
                                                            ->label('PIN'),
                                                    ])->columns(1),
                                            ])
                                            ->columnSpan(['lg' => 1]),
                                    ])
                                    ->columns(3),
                            ]),
                    ])
                    ->columnSpan('full')
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('partner.avatar')
                        ->height(150)
                        ->width(200),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('job_title')
                            ->label('Job Title'),
                        Tables\Columns\TextColumn::make('work_email')
                            ->icon('heroicon-o-envelope')
                            ->label('Work Email')
                            ->color('gray')
                            ->limit(30)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('work_phone')
                            ->icon('heroicon-o-phone')
                            ->label('Work Email')
                            ->color('gray')
                            ->limit(30)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('categories.name')
                            ->badge(),
                    ])->space(1),
                ])->space(4),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
            ])
            ->paginated([
                18,
                36,
                72,
                'all',
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label('Company')
                    ->collapsible(),
                Tables\Grouping\Group::make('manager.name')
                    ->label('Manager')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label('Update At')
                    ->date()
                    ->collapsible(),
            ])
            ->defaultSort('name')
            ->persistSortInSession()
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->outlined(),
                Tables\Actions\EditAction::make()
                    ->outlined(),
                Tables\Actions\RestoreAction::make()
                    ->outlined(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Employee')
                    ->modalDescription('Are you sure you want to delete this employee?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function () {
                            Notification::make()
                                ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                                ->warning()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getBankCreateSchema(): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Bank Name')
                        ->required(),
                    Forms\Components\TextInput::make('code')
                        ->label('Bank Code')
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel(),
                    Forms\Components\TextInput::make('street1')
                        ->label('Street 1'),
                    Forms\Components\TextInput::make('street2')
                        ->label('Street 2'),
                    Forms\Components\TextInput::make('city')
                        ->label('City'),
                    Forms\Components\TextInput::make('zip')
                        ->label('ZIP/Postal Code'),
                    Forms\Components\Select::make('country_id')
                        ->label('Country')
                        ->relationship(name: 'country', titleAttribute: 'name')
                        ->afterStateUpdated(fn(Set $set) => $set('state_id', null))
                        ->createOptionForm([
                            Forms\Components\Select::make('currency_id')
                                ->options(fn() => Currency::pluck('full_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->label('Currency Name')
                                ->required(),
                            Forms\Components\TextInput::make('phone_code')
                                ->label('Phone Code')
                                ->required(),
                            Forms\Components\TextInput::make('code')
                                ->label('Code')
                                ->required()
                                ->rules('max:2'),
                            Forms\Components\TextInput::make('name')
                                ->label('Country Name')
                                ->required(),
                            Forms\Components\Toggle::make('state_required')
                                ->label('State Required')
                                ->required(),
                            Forms\Components\Toggle::make('zip_required')
                                ->label('Zip Required')
                                ->required(),
                        ])
                        ->createOptionAction(
                            fn(Action $action) => $action
                                ->modalHeading('Create Country')
                                ->modalSubmitActionLabel('Create Country')
                                ->modalWidth('lg')
                        )
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                    Forms\Components\Select::make('state_id')
                        ->relationship('state', 'name')
                        ->searchable()
                        ->preload()
                        ->label('State')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('code')
                                ->label('Code')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->createOptionAction(
                            fn(Action $action) => $action
                                ->modalHeading('Create State')
                                ->modalSubmitActionLabel('Create State')
                                ->modalWidth('lg')
                        ),
                    Forms\Components\Hidden::make('creator_id')
                        ->default(fn() => Auth::user()->id),
                ])->columns(2),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SkillsRelationManager::class,
            RelationManagers\ResumeRelationManager::class,
        ];
    }

    public static function getSlug(): string
    {
        return 'employees/employees';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit'   => Pages\EditEmployee::route('/{record}/edit'),
            'view'   => Pages\ViewEmployee::route('/{record}'),
        ];
    }
}
