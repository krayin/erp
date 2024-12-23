<?php

namespace Webkul\Employee\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Enums\DistanceUnit;
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
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Security\Filament\Resources\CompanyResource;
use Webkul\Security\Filament\Resources\UserResource;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;

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
                                            ->hiddenLabel()
                                            ->imageResizeMode('cover')
                                            ->imageEditor()
                                            ->alignRight()
                                            ->avatar()
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
                                            ->url(fn (?string $state) => $state ? "mailto:{$state}" : '#')
                                    )
                                    ->email(),
                                Forms\Components\Select::make('department_id')
                                    ->label('Department')
                                    ->relationship(name: 'department', titleAttribute: 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(fn (Form $form) => DepartmentResource::form($form)),
                                Forms\Components\TextInput::make('mobile_phone')
                                    ->label('Work Mobile')
                                    ->suffixAction(
                                        Action::make('open_mobile_phone')
                                            ->icon('heroicon-o-phone')
                                            ->color('blue')
                                            ->action(function (Set $set, $state) {
                                                $set('mobile_phone', $state);
                                            })
                                            ->url(fn (?string $state) => $state ? "tel:{$state}" : '#')
                                    )
                                    ->tel(),
                                Forms\Components\Select::make('job_id')
                                    ->relationship('job', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label('Job Position')
                                    ->createOptionForm(fn (Form $form) => JobPositionResource::form($form)),
                                Forms\Components\TextInput::make('work_phone')
                                    ->label('Work Phone')
                                    ->suffixAction(
                                        Action::make('open_work_phone')
                                            ->icon('heroicon-o-phone')
                                            ->color('blue')
                                            ->action(function (Set $set, $state) {
                                                $set('work_phone', $state);
                                            })
                                            ->url(fn (?string $state) => $state ? "tel:{$state}" : '#')
                                    )
                                    ->tel(),
                                Forms\Components\Select::make('parent_id')
                                    ->options(fn () => Employee::pluck('name', 'id'))
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
                                    ->createOptionForm(fn (Form $form) => EmployeeCategoryResource::form($form)),
                                Forms\Components\Select::make('coach_id')
                                    ->searchable()
                                    ->preload()
                                    ->options(fn () => Employee::pluck('name', 'id'))
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
                                                            ->options(fn () => Company::pluck('name', 'id'))
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->suffixIcon('heroicon-o-map-pin')
                                                            ->label('Work Address'),
                                                        Forms\Components\Placeholder::make('address')
                                                            ->hiddenLabel()
                                                            ->hidden(fn (Get $get) => ! Company::find($get('address_id'))?->address)
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
                                                            }),
                                                        Forms\Components\Select::make('work_location_id')
                                                            ->relationship('workLocation', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Work Location')
                                                            ->prefixIcon('heroicon-o-map-pin')
                                                            ->createOptionForm(fn (Form $form) => WorkLocationResource::form($form))
                                                            ->editOptionForm(fn (Form $form) => WorkLocationResource::form($form)),
                                                    ])->columns(1),
                                                Forms\Components\Fieldset::make('Approvers')
                                                    ->schema([
                                                        Forms\Components\Select::make('leave_manager_id')
                                                            ->options(fn () => User::pluck('name', 'id'))
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->suffixIcon('heroicon-o-clock')
                                                            ->label('Time Off'),
                                                        Forms\Components\Select::make('attendance_manager_id')
                                                            ->options(fn () => User::pluck('name', 'id'))
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->suffixIcon('heroicon-o-clock')
                                                            ->label('Attendance'),
                                                    ])->columns(1),
                                                Forms\Components\Fieldset::make('Schedule')
                                                    ->schema([
                                                        Forms\Components\Select::make('calendar_id')
                                                            ->options(fn () => Calendar::pluck('name', 'id'))
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
                                                                    ->createOptionForm(fn (Form $form) => CompanyResource::form($form)),
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
                                                        Forms\Components\Fieldset::make('Permanent Address')
                                                            ->relationship('permanentAddress')
                                                            ->schema([
                                                                Forms\Components\Select::make('country_id')
                                                                    ->label('Country')
                                                                    ->relationship(name: 'country', titleAttribute: 'name')
                                                                    ->createOptionForm([
                                                                        Forms\Components\TextInput::make('name')
                                                                            ->label('Country Name')
                                                                            ->required(),
                                                                        Forms\Components\TextInput::make('code')
                                                                            ->label('Code')
                                                                            ->required()
                                                                            ->rules('max:2'),
                                                                        Forms\Components\Toggle::make('state_required')
                                                                            ->label('State Required')
                                                                            ->required(),
                                                                        Forms\Components\Toggle::make('zip_required')
                                                                            ->label('Zip Required')
                                                                            ->required(),
                                                                    ])
                                                                    ->createOptionAction(
                                                                        fn (Action $action) => $action
                                                                            ->modalHeading('Create Country')
                                                                            ->modalSubmitActionLabel('Create Country')
                                                                            ->modalWidth('lg')
                                                                    )
                                                                    ->afterStateUpdated(fn (Set $set) => $set('state_id', null))
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->live(),
                                                                Forms\Components\Select::make('state_id')
                                                                    ->label('State')
                                                                    ->options(
                                                                        fn (Get $get) => State::query()
                                                                            ->where('country_id', $get('country_id'))
                                                                            ->pluck('name', 'id')
                                                                    )
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
                                                                        fn (Action $action) => $action
                                                                            ->modalHeading('Create State')
                                                                            ->modalSubmitActionLabel('Create State')
                                                                            ->modalWidth('lg')
                                                                    )
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->required(fn (Get $get) => Country::find($get('country_id'))?->state_required),
                                                                Forms\Components\TextInput::make('street1')
                                                                    ->label('Street Address'),
                                                                Forms\Components\TextInput::make('street2')
                                                                    ->label('Street Address Line 2'),
                                                                Forms\Components\TextInput::make('city')
                                                                    ->label('City'),
                                                                Forms\Components\TextInput::make('zip')
                                                                    ->label('Postal Code')
                                                                    ->required(fn (Get $get) => Country::find($get('country_id'))?->zip_required),
                                                                Forms\Components\Hidden::make('type')
                                                                    ->default('permanent'),
                                                                Forms\Components\Hidden::make('creator_id')
                                                                    ->default(Auth::user()->id),
                                                            ]),
                                                        Forms\Components\Fieldset::make('Present Address')
                                                            ->relationship('presentAddress')
                                                            ->schema([
                                                                Forms\Components\Hidden::make('is_primary')
                                                                    ->default(true)
                                                                    ->required(),
                                                                Forms\Components\Select::make('country_id')
                                                                    ->label('Country')
                                                                    ->relationship(name: 'country', titleAttribute: 'name')
                                                                    ->createOptionForm([
                                                                        Forms\Components\TextInput::make('name')
                                                                            ->label('Country Name')
                                                                            ->required(),
                                                                        Forms\Components\TextInput::make('code')
                                                                            ->label('Code')
                                                                            ->required()
                                                                            ->rules('max:2'),
                                                                        Forms\Components\Toggle::make('state_required')
                                                                            ->label('State Required')
                                                                            ->required(),
                                                                        Forms\Components\Toggle::make('zip_required')
                                                                            ->label('Zip Required')
                                                                            ->required(),
                                                                    ])
                                                                    ->createOptionAction(
                                                                        fn (Action $action) => $action
                                                                            ->modalHeading('Create Country')
                                                                            ->modalSubmitActionLabel('Create Country')
                                                                            ->modalWidth('lg')
                                                                    )
                                                                    ->afterStateUpdated(fn (Set $set) => $set('state_id', null))
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->live(),
                                                                Forms\Components\Select::make('state_id')
                                                                    ->label('State')
                                                                    ->options(
                                                                        fn (Get $get) => State::query()
                                                                            ->where('country_id', $get('country_id'))
                                                                            ->pluck('name', 'id')
                                                                    )
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
                                                                        fn (Action $action) => $action
                                                                            ->modalHeading('Create State')
                                                                            ->modalSubmitActionLabel('Create State')
                                                                            ->modalWidth('lg')
                                                                    )
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->required(fn (Get $get) => Country::find($get('country_id'))?->state_required),
                                                                Forms\Components\TextInput::make('street1')
                                                                    ->label('Street Address'),
                                                                Forms\Components\TextInput::make('street2')
                                                                    ->label('Street Address Line 2'),
                                                                Forms\Components\TextInput::make('city')
                                                                    ->label('City'),
                                                                Forms\Components\TextInput::make('zip')
                                                                    ->label('Postal Code')
                                                                    ->required(fn (Get $get) => Country::find($get('country_id'))?->zip_required),
                                                                Forms\Components\Hidden::make('type')
                                                                    ->default('present'),
                                                                Forms\Components\Hidden::make('creator_id')
                                                                    ->default(Auth::user()->id),
                                                            ]),
                                                        Forms\Components\Fieldset::make('Private Contact')
                                                            ->schema([
                                                                Forms\Components\TextInput::make('private_phone')
                                                                    ->label('Private Phone')
                                                                    ->suffixAction(
                                                                        Action::make('open_private_phone')
                                                                            ->icon('heroicon-o-phone')
                                                                            ->color('blue')
                                                                            ->action(function (Set $set, $state) {
                                                                                $set('private_phone', $state);
                                                                            })
                                                                            ->url(fn (?string $state) => $state ? "tel:{$state}" : '#')
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
                                                                                    ->default(fn () => Auth::user()->id),
                                                                                Forms\Components\Select::make('bank_id')
                                                                                    ->relationship('bank', 'name')
                                                                                    ->label('Bank')
                                                                                    ->searchable()
                                                                                    ->preload()
                                                                                    ->createOptionForm(static::getBankCreateSchema())
                                                                                    ->editOptionForm(static::getBankCreateSchema())
                                                                                    ->createOptionAction(fn (Action $action) => $action->modalHeading('Create Bank'))
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
                                                                        fn (Action $action) => $action
                                                                            ->modalHeading('Create Bank Account')
                                                                            ->modalSubmitActionLabel('Create Bank Account')
                                                                    )
                                                                    ->disabled(fn ($livewire) => ! $livewire->record?->user)
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
                                                                            ->url(fn (?string $state) => $state ? "mailto:{$state}" : '#')
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
                                                                Forms\Components\Select::make('distance_home_work_unit')
                                                                    ->options(DistanceUnit::options())
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
                                                                                    ->url(fn (?string $state) => $state ? "tel:{$state}" : '#')
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
                                                                    ->hidden(fn (Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->dehydrated(fn (Get $get) => $get('marital') !== MaritalStatus::Single->value)
                                                                    ->required(fn (Get $get) => $get('marital') !== MaritalStatus::Single->value),
                                                                Forms\Components\DatePicker::make('spouse_birthdate')
                                                                    ->label('Spouse Birthdate')
                                                                    ->native(false)
                                                                    ->suffixIcon('heroicon-o-calendar')
                                                                    ->disabled(fn (Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->hidden(fn (Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->dehydrated(fn (Get $get) => $get('marital') !== MaritalStatus::Single->value),
                                                                Forms\Components\TextInput::make('children')
                                                                    ->label('Number of Children')
                                                                    ->numeric()
                                                                    ->minValue(0)
                                                                    ->disabled(fn (Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->hidden(fn (Get $get) => $get('marital') === MaritalStatus::Single->value)
                                                                    ->dehydrated(fn (Get $get) => $get('marital') !== MaritalStatus::Single->value),
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
                                                            ->createOptionForm([
                                                                Forms\Components\TextInput::make('name')
                                                                    ->label('Country Name')
                                                                    ->required(),
                                                                Forms\Components\TextInput::make('code')
                                                                    ->label('Code')
                                                                    ->required()
                                                                    ->rules('max:2'),
                                                                Forms\Components\Toggle::make('state_required')
                                                                    ->label('State Required')
                                                                    ->required(),
                                                                Forms\Components\Toggle::make('zip_required')
                                                                    ->label('Zip Required')
                                                                    ->required(),
                                                            ])
                                                            ->createOptionAction(
                                                                fn (Action $action) => $action
                                                                    ->modalHeading('Create Country')
                                                                    ->modalSubmitActionLabel('Create Country')
                                                                    ->modalWidth('lg')
                                                            )
                                                            ->afterStateUpdated(fn (Set $set) => $set('state_id', null))
                                                            ->searchable()
                                                            ->preload()
                                                            ->live(),
                                                        Forms\Components\Select::make('state_id')
                                                            ->label('State')
                                                            ->options(
                                                                fn (Get $get) => State::query()
                                                                    ->where('country_id', $get('country_id'))
                                                                    ->pluck('name', 'id')
                                                            )
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
                                                                fn (Action $action) => $action
                                                                    ->modalHeading('Create State')
                                                                    ->modalSubmitActionLabel('Create State')
                                                                    ->modalWidth('lg')
                                                            )
                                                            ->searchable()
                                                            ->preload()
                                                            ->required(fn (Get $get) => Country::find($get('country_id'))?->state_required),
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
                                                            ->suffixIcon('heroicon-o-calendar')
                                                            ->native(false),
                                                        Forms\Components\DatePicker::make('work_permit_expiration_date')
                                                            ->label('Work Permit Expiration Date')
                                                            ->suffixIcon('heroicon-o-calendar')
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
                                                            ->relationship(name: 'user', titleAttribute: 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Related User')
                                                            ->prefixIcon('heroicon-o-user')
                                                            ->createOptionForm(fn (Form $form) => UserResource::form($form))
                                                            ->createOptionAction(
                                                                fn (Action $action, Get $get) => $action
                                                                    ->fillForm(function (array $arguments) use ($get): array {
                                                                        return [
                                                                            'name'  => $get('name'),
                                                                            'email' => $get('work_email'),
                                                                        ];
                                                                    })
                                                                    ->modalHeading('Create User')
                                                                    ->modalSubmitActionLabel('Create User')
                                                                    ->action(function (array $data, $component) {
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
                                                            ->createOptionForm(fn (Form $form) => DepartureReasonResource::form($form)),
                                                        Forms\Components\DatePicker::make('departure_date')
                                                            ->label('Departure Date')
                                                            ->native(false)
                                                            ->hidden(fn (Get $get) => $get('departure_reason_id') === null)
                                                            ->disabled(fn (Get $get) => $get('departure_reason_id') === null)
                                                            ->required(fn (Get $get) => $get('departure_reason_id') !== null),
                                                        Forms\Components\Textarea::make('departure_description')
                                                            ->label('Departure Description')
                                                            ->hidden(fn (Get $get) => $get('departure_reason_id') === null)
                                                            ->disabled(fn (Get $get) => $get('departure_reason_id') === null)
                                                            ->required(fn (Get $get) => $get('departure_reason_id') !== null),
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
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('job_title')
                                ->icon('heroicon-m-briefcase')
                                ->searchable()
                                ->sortable()
                                ->label('Job Title'),
                        ])
                            ->visible(fn ($record) => filled($record->job_title)),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('work_email')
                                ->icon('heroicon-o-envelope')
                                ->searchable()
                                ->sortable()
                                ->label('Work Email')
                                ->color('gray')
                                ->limit(20),
                        ])
                            ->visible(fn ($record) => filled($record->work_email)),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('work_phone')
                                ->icon('heroicon-o-phone')
                                ->searchable()
                                ->label('Work Phone')
                                ->color('gray')
                                ->limit(30)
                                ->sortable(),
                        ])
                            ->visible(fn ($record) => filled($record->work_phone)),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('categories.name')
                                ->badge()
                                ->weight(FontWeight::Bold),
                        ])
                            ->visible(fn ($record): bool => (bool) $record->categories()->get()?->count()),
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
            ->filtersFormColumns(3)
            ->filters([
                Tables\Filters\SelectFilter::make('skills')
                    ->relationship('skills.skill', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('resumes')
                    ->relationship('resumes', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('time_zone')
                    ->options(function () {
                        return collect(timezone_identifiers_list())->mapWithKeys(function ($timezone) {
                            return [$timezone => $timezone];
                        });
                    })
                    ->searchable()
                    ->multiple()
                    ->preload(),
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(5)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('job_title')
                            ->label('Job Title')
                            ->icon('heroicon-o-user-circle'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('birthday')
                            ->label('Birthday')
                            ->icon('heroicon-o-cake'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('work_email')
                            ->label('Work Email')
                            ->icon('heroicon-o-at-symbol'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('mobile_phone')
                            ->label('Work Mobile')
                            ->icon('heroicon-o-phone'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('work_phone')
                            ->label('Work Phone')
                            ->icon('heroicon-o-phone'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('is_flexible')
                            ->label('Is Flexible')
                            ->icon('heroicon-o-cube'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('is_fully_flexible')
                            ->label('Is Fully Flexible')
                            ->icon('heroicon-o-cube'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('is_active')
                            ->label('Is Active')
                            ->icon('heroicon-o-cube'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('work_permit_scheduled_activity')
                            ->label('Work Permit Scheduled Activity')
                            ->icon('heroicon-o-cube'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('emergency_contact')
                            ->label('Emergency Contact')
                            ->icon('heroicon-o-phone'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('emergency_phone')
                            ->label('Emergency Phone')
                            ->icon('heroicon-o-phone'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('private_phone')
                            ->label('Private Phone')
                            ->icon('heroicon-o-phone'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('private_email')
                            ->label('Private Email')
                            ->icon('heroicon-o-at-symbol'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('private_car_plate')
                            ->label('Private Car Plate')
                            ->icon('heroicon-o-clipboard-document'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('distance_home_work')
                            ->label('Distance Home Work')
                            ->icon('heroicon-o-map'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('km_home_work')
                            ->label('Km Home Work')
                            ->icon('heroicon-o-map'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('distance_home_work_unit')
                            ->label('Distance Home Work Unit')
                            ->icon('heroicon-o-map'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('marital')
                            ->label('Marital Status')
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('spouse_complete_name')
                            ->label('Spouse Name')
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('spouse_birthdate')
                            ->label('Spouse Birthdate')
                            ->icon('heroicon-o-cake'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('certificate')
                            ->label('Certificate')
                            ->icon('heroicon-o-document'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('study_field')
                            ->label('Study Field')
                            ->icon('heroicon-o-academic-cap'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('study_school')
                            ->label('Study School')
                            ->icon('heroicon-o-academic-cap'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('identification_id')
                            ->label('Identification Id')
                            ->icon('heroicon-o-credit-card'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('ssnid')
                            ->label('SSNID')
                            ->icon('heroicon-o-credit-card'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('sinid')
                            ->label('SINID')
                            ->icon('heroicon-o-credit-card'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('passport_id')
                            ->label('Passport ID')
                            ->icon('heroicon-o-credit-card'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('gender')
                            ->label('Gender')
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('children')
                            ->label('Children')
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('visa_no')
                            ->label('Visa No')
                            ->icon('heroicon-o-credit-card'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('permit_no')
                            ->label('Permit No')
                            ->icon('heroicon-o-credit-card'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('lang')
                            ->label('Language')
                            ->icon('heroicon-o-language'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('additional_note')
                            ->label('Additional Note')
                            ->icon('heroicon-o-language'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('notes')
                            ->label('Notes')
                            ->icon('heroicon-o-language'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('barcode')
                            ->label('Barcode')
                            ->icon('heroicon-o-qr-code'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('visa_expire')
                            ->label('Visa Expire')
                            ->icon('heroicon-o-credit-card'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('work_permit_expiration_date')
                            ->label('Work Permit Expiration Date')
                            ->icon('heroicon-o-calendar'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('departure_date')
                            ->label('Departure Date')
                            ->icon('heroicon-o-calendar'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('departure_description')
                            ->label('Departure Description')
                            ->icon('heroicon-o-cube'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('visa_no')
                            ->label('Visa No')
                            ->icon('heroicon-o-credit-card'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label('Company')
                            ->icon('heroicon-o-building-office-2')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('creator')
                            ->label('Created By')
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('calendar')
                            ->label('Calendar')
                            ->icon('heroicon-o-calendar')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('department')
                            ->label('Department')
                            ->multiple()
                            ->icon('heroicon-o-building-office-2')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('job')
                            ->label('Job')
                            ->icon('heroicon-o-briefcase')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('partner')
                            ->label('Partner')
                            ->icon('heroicon-o-user-group')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('leaveManager')
                            ->label('Leave Approvers')
                            ->icon('heroicon-o-user-group')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('attendanceManager')
                            ->label('Attendance')
                            ->icon('heroicon-o-user-group')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('workLocation')
                            ->label('Work Location')
                            ->multiple()
                            ->icon('heroicon-o-map-pin')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('parent')
                            ->label('Manager')
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('coach')
                            ->label('Coach')
                            ->multiple()
                            ->icon('heroicon-o-user')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('privateState')
                            ->label('Private State')
                            ->multiple()
                            ->icon('heroicon-o-map-pin')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('privateCountry')
                            ->label('Private Country')
                            ->icon('heroicon-o-map-pin')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('country')
                            ->label('Country')
                            ->icon('heroicon-o-map-pin')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('state')
                            ->label('State')
                            ->icon('heroicon-o-map-pin')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('countryOfBirth')
                            ->label('Country Of Birth')
                            ->multiple()
                            ->icon('heroicon-o-calendar')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('bankAccount')
                            ->label('Bank Account')
                            ->multiple()
                            ->icon('heroicon-o-banknotes')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('account_holder_name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('departureReason')
                            ->label('Departure Reason')
                            ->icon('heroicon-o-fire')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('employmentType')
                            ->label('Employment Type')
                            ->multiple()
                            ->icon('heroicon-o-academic-cap')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('categories')
                            ->label('Tags')
                            ->icon('heroicon-o-tag')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                    ]),

            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label('Company')
                    ->collapsible(),
                Tables\Grouping\Group::make('parent.name')
                    ->label('Manager')
                    ->collapsible(),
                Tables\Grouping\Group::make('coach.name')
                    ->label('Coach')
                    ->collapsible(),
                Tables\Grouping\Group::make('department.name')
                    ->label('Department')
                    ->collapsible(),
                Tables\Grouping\Group::make('employmentType.name')
                    ->label('Employment Type')
                    ->collapsible(),
                Tables\Grouping\Group::make('categories.name')
                    ->label('Tags')
                    ->collapsible(),
                Tables\Grouping\Group::make('departureReason.name')
                    ->label('Departure Reason')
                    ->collapsible(),
                Tables\Grouping\Group::make('privateState.name')
                    ->label('Private State')
                    ->collapsible(),
                Tables\Grouping\Group::make('privateCountry.name')
                    ->label('Private Country')
                    ->collapsible(),
                Tables\Grouping\Group::make('country.name')
                    ->label('Country')
                    ->collapsible(),
                Tables\Grouping\Group::make('state.name')
                    ->label('State')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->date()
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\Grid::make(['default' => 2])
                            ->schema([
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('name')
                                        ->label('Name')
                                        ->weight(FontWeight::Bold)
                                        ->placeholder('—')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                                    Infolists\Components\TextEntry::make('job_title')
                                        ->placeholder('—')
                                        ->label('Job Title'),
                                ])->columnSpan(1),
                                Infolists\Components\Group::make([
                                    Infolists\Components\ImageEntry::make('partner.avatar')
                                        ->label('Profile Picture')
                                        ->height(140)
                                        ->circular(),
                                ])->columnSpan(1),
                            ]),
                        Infolists\Components\Grid::make(['default' => 2])
                            ->schema([
                                Infolists\Components\TextEntry::make('work_email')
                                    ->label('Work Email')
                                    ->placeholder('—')
                                    ->url(fn (?string $state) => $state ? "mailto:{$state}" : '#')
                                    ->icon('heroicon-o-envelope')
                                    ->iconPosition(IconPosition::Before),
                                Infolists\Components\TextEntry::make('department.name')
                                    ->placeholder('—')
                                    ->label('Department'),
                                Infolists\Components\TextEntry::make('mobile_phone')
                                    ->label('Work Mobile')
                                    ->placeholder('—')
                                    ->url(fn (?string $state) => $state ? "tel:{$state}" : '#')
                                    ->icon('heroicon-o-phone')
                                    ->iconPosition(IconPosition::Before),
                                Infolists\Components\TextEntry::make('job.name')
                                    ->placeholder('—')
                                    ->label('Job Position'),
                                Infolists\Components\TextEntry::make('work_phone')
                                    ->placeholder('—')
                                    ->label('Work Phone')
                                    ->url(fn (?string $state) => $state ? "tel:{$state}" : '#')
                                    ->icon('heroicon-o-phone')
                                    ->iconPosition(IconPosition::Before),
                                Infolists\Components\TextEntry::make('parent.name')
                                    ->placeholder('—')
                                    ->label('Manager'),
                                Infolists\Components\TextEntry::make('categories.name')
                                    ->placeholder('—')
                                    ->label('Employee Tags')
                                    ->listWithLineBreaks()
                                    ->bulleted(),
                                Infolists\Components\TextEntry::make('coach.name')
                                    ->placeholder('—')
                                    ->label('Coach'),
                            ]),
                    ]),

                Tabs::make('Employee Information')
                    ->tabs([
                        Tabs\Tab::make('Work Information')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Infolists\Components\Grid::make(['default' => 3])
                                    ->schema([
                                        Infolists\Components\Group::make([
                                            Infolists\Components\Fieldset::make('Location')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('companyAddress.company.name')
                                                        ->label('Work Address')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-map'),
                                                    Infolists\Components\TextEntry::make('address')
                                                        ->visible(fn ($record) => $record->address)
                                                        ->placeholder('—')
                                                        ->formatStateUsing(fn ($record) => $record->address
                                                            ? implode(', ', array_filter([
                                                                $record->address->street1,
                                                                $record->address->street2,
                                                                $record->address->city,
                                                                $record->address->state?->name,
                                                                $record->address->country?->name,
                                                                $record->address->zip,
                                                            ]))
                                                            : 'No Address Available')
                                                        ->icon('heroicon-o-map')
                                                        ->hiddenLabel(),
                                                    Infolists\Components\TextEntry::make('workLocation.name')
                                                        ->placeholder('—')
                                                        ->label('Work Location')
                                                        ->icon('heroicon-o-building-office'),
                                                ]),
                                            Infolists\Components\Fieldset::make('Approvers')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('leaveManager.name')
                                                        ->label('Time Off')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-user-group'),
                                                    Infolists\Components\TextEntry::make('attendanceManager.name')
                                                        ->placeholder('—')
                                                        ->label('Attendance')
                                                        ->icon('heroicon-o-user-group'),
                                                ]),
                                            Infolists\Components\Fieldset::make('Schedule')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('calendar.name')
                                                        ->label('Working Hours')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-clock'),
                                                    Infolists\Components\TextEntry::make('time_zone')
                                                        ->label('Time Zone')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-clock'),
                                                ]),
                                        ])->columnSpan(2),
                                        Infolists\Components\Group::make([
                                            Infolists\Components\Fieldset::make('Organizational Details')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('company.name')
                                                        ->label('Company')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-briefcase'),
                                                    Infolists\Components\ColorEntry::make('color')
                                                        ->placeholder('—')
                                                        ->label('Color'),
                                                ]),
                                        ])->columnSpan(1),
                                    ]),
                            ]),
                        Tabs\Tab::make('Private Information')
                            ->icon('heroicon-o-lock-closed')
                            ->schema([
                                Infolists\Components\Grid::make(['default' => 3])
                                    ->schema([
                                        Infolists\Components\Group::make([
                                            Infolists\Components\Fieldset::make('Permanent Address')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('permanentAddress.country.name')
                                                        ->label('Country')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-globe-alt'),
                                                    Infolists\Components\TextEntry::make('permanentAddress.state.name')
                                                        ->label('State')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-map'),
                                                    Infolists\Components\TextEntry::make('permanentAddress.street1')
                                                        ->label('Street Address')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-map'),
                                                    Infolists\Components\TextEntry::make('permanentAddress.street2')
                                                        ->label('Street Address Line 2')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-map'),
                                                    Infolists\Components\TextEntry::make('permanentAddress.city')
                                                        ->label('City')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-building-office'),
                                                    Infolists\Components\TextEntry::make('permanentAddress.zip')
                                                        ->label('Postal Code')
                                                        ->icon('heroicon-o-document-text'),
                                                ])
                                                ->columns(2),
                                            Infolists\Components\Fieldset::make('Present Address')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('presentAddress.country.name')
                                                        ->label('Country')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-globe-alt'),
                                                    Infolists\Components\TextEntry::make('presentAddress.state.name')
                                                        ->label('State')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-map'),
                                                    Infolists\Components\TextEntry::make('presentAddress.street1')
                                                        ->label('Street Address')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-map'),
                                                    Infolists\Components\TextEntry::make('presentAddress.street2')
                                                        ->label('Street Address Line 2')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-map'),
                                                    Infolists\Components\TextEntry::make('presentAddress.city')
                                                        ->label('City')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-building-office'),
                                                    Infolists\Components\TextEntry::make('presentAddress.zip')
                                                        ->label('Postal Code')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-document-text'),
                                                ])
                                                ->columns(2),
                                            Infolists\Components\Fieldset::make('Private Contact')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('private_phone')
                                                        ->label('Private Phone')
                                                        ->placeholder('—')
                                                        ->url(fn (?string $state) => $state ? "tel:{$state}" : '#')
                                                        ->icon('heroicon-o-phone'),
                                                    Infolists\Components\TextEntry::make('private_email')
                                                        ->label('Private Email')
                                                        ->placeholder('—')
                                                        ->url(fn (?string $state) => $state ? "mailto:{$state}" : '#')
                                                        ->icon('heroicon-o-envelope'),
                                                    Infolists\Components\TextEntry::make('private_car_plate')
                                                        ->label('Private Car Plate')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-rectangle-stack'),
                                                    Infolists\Components\TextEntry::make('distance_home_work')
                                                        ->label('Distance Home to Work')
                                                        ->placeholder('—')
                                                        ->suffix('km')
                                                        ->icon('heroicon-o-map'),
                                                ]),
                                            Infolists\Components\Fieldset::make('Emergency Contact')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('emergency_contact')
                                                        ->label('Contact Name')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-user'),
                                                    Infolists\Components\TextEntry::make('emergency_phone')
                                                        ->label('Contact Phone')
                                                        ->placeholder('—')
                                                        ->url(fn (?string $state) => $state ? "tel:{$state}" : '#')
                                                        ->icon('heroicon-o-phone'),
                                                ]),
                                            Infolists\Components\Fieldset::make('Work Permit')
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('visa_no')
                                                        ->label('Visa Number')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-document-text')
                                                        ->copyable()
                                                        ->copyMessage('Visa number copied')
                                                        ->copyMessageDuration(1500),
                                                    Infolists\Components\TextEntry::make('permit_no')
                                                        ->label('Work Permit No')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-rectangle-stack')
                                                        ->copyable()
                                                        ->copyMessage('Permit number copied')
                                                        ->copyMessageDuration(1500),
                                                    Infolists\Components\TextEntry::make('visa_expire')
                                                        ->label('Visa Expiration Date')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-calendar-days')
                                                        ->date('F j, Y')
                                                        ->color(
                                                            fn ($record) => $record->visa_expire && now()->diffInDays($record->visa_expire, false) <= 30
                                                                ? 'danger'
                                                                : 'success'
                                                        ),
                                                    Infolists\Components\TextEntry::make('work_permit_expiration_date')
                                                        ->label('Work Permit Expiration Date')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-calendar-days')
                                                        ->date('F j, Y')
                                                        ->color(
                                                            fn ($record) => $record->work_permit_expiration_date && now()->diffInDays($record->work_permit_expiration_date, false) <= 30
                                                                ? 'danger'
                                                                : 'success'
                                                        ),
                                                    Infolists\Components\ImageEntry::make('work_permit')
                                                        ->label('Work Permit Document')
                                                        ->columnSpanFull()
                                                        ->placeholder('—')
                                                        ->height(200),
                                                ]),
                                        ])->columnSpan(2),
                                        Infolists\Components\Group::make([
                                            Infolists\Components\Fieldset::make('Citizenship')
                                                ->columns(1)
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('country.name')
                                                        ->label('Country')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-globe-alt'),
                                                    Infolists\Components\TextEntry::make('state.name')
                                                        ->label('State')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-map'),
                                                    Infolists\Components\TextEntry::make('identification_id')
                                                        ->label('Identification No')
                                                        ->icon('heroicon-o-document-text')
                                                        ->placeholder('—')
                                                        ->copyable()
                                                        ->copyMessage('ID copied')
                                                        ->copyMessageDuration(1500),
                                                    Infolists\Components\TextEntry::make('ssnid')
                                                        ->label('SSN No')
                                                        ->icon('heroicon-o-document-check')
                                                        ->placeholder('—')
                                                        ->copyable()
                                                        ->copyMessage('SSN copied')
                                                        ->copyMessageDuration(1500),
                                                    Infolists\Components\TextEntry::make('sinid')
                                                        ->label('SIN ID')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-document')
                                                        ->copyable()
                                                        ->copyMessage('SIN ID copied')
                                                        ->copyMessageDuration(1500),
                                                    Infolists\Components\TextEntry::make('passport_id')
                                                        ->label('Passport No')
                                                        ->icon('heroicon-o-identification')
                                                        ->copyable()
                                                        ->placeholder('—')
                                                        ->copyMessage('Passport copied')
                                                        ->copyMessageDuration(1500),
                                                    Infolists\Components\TextEntry::make('gender')
                                                        ->label('Gender')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-user')
                                                        ->badge()
                                                        ->color(fn (string $state): string => match ($state) {
                                                            'male'   => 'info',
                                                            'female' => 'success',
                                                            default  => 'warning',
                                                        }),
                                                    Infolists\Components\TextEntry::make('birthday')
                                                        ->label('Date of Birth')
                                                        ->icon('heroicon-o-calendar')
                                                        ->placeholder('—')
                                                        ->date('F j, Y'),
                                                    Infolists\Components\TextEntry::make('countryOfBirth.name')
                                                        ->label('Country of Birth')
                                                        ->placeholder('—')
                                                        ->icon('heroicon-o-globe-alt'),
                                                    Infolists\Components\TextEntry::make('country.phone_code')
                                                        ->label('Phone Code')
                                                        ->icon('heroicon-o-phone')
                                                        ->placeholder('—')
                                                        ->prefix('+'),
                                                ]),
                                        ])->columnSpan(1),
                                    ]),
                            ]),
                        Tabs\Tab::make('Settings')
                            ->icon('heroicon-o-cog-8-tooth')
                            ->schema([
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\Group::make()
                                            ->schema([
                                                Infolists\Components\Fieldset::make('Employment Status')
                                                    ->schema([
                                                        Infolists\Components\IconEntry::make('is_active')
                                                            ->label('Active Employee')
                                                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                                                        Infolists\Components\IconEntry::make('is_flexible')
                                                            ->label('Flexible Work Arrangement')
                                                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                                                        Infolists\Components\IconEntry::make('is_fully_flexible')
                                                            ->label('Fully Flexible Schedule')
                                                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                                                        Infolists\Components\IconEntry::make('work_permit_scheduled_activity')
                                                            ->label('Work Permit Scheduled Activity')
                                                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                                                        Infolists\Components\TextEntry::make('user.name')
                                                            ->label('Related User')
                                                            ->placeholder('—')
                                                            ->icon('heroicon-o-user'),
                                                        Infolists\Components\TextEntry::make('departureReason.name')
                                                            ->placeholder('—')
                                                            ->label('Departure Reason'),
                                                        Infolists\Components\TextEntry::make('departure_date')
                                                            ->placeholder('—')
                                                            ->label('Departure Date')
                                                            ->icon('heroicon-o-calendar-days'),
                                                        Infolists\Components\TextEntry::make('departure_description')
                                                            ->placeholder('—')
                                                            ->label('Departure Description'),
                                                    ])
                                                    ->columns(2),
                                                Infolists\Components\Fieldset::make('Additional Information')
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('lang')
                                                            ->placeholder('—')
                                                            ->label('Primary Language'),
                                                        Infolists\Components\TextEntry::make('additional_note')
                                                            ->placeholder('—')
                                                            ->label('Additional Notes')
                                                            ->columnSpanFull(),
                                                        Infolists\Components\TextEntry::make('notes')
                                                            ->placeholder('—')
                                                            ->label('Notes'),
                                                    ])
                                                    ->columns(2),
                                            ])
                                            ->columnSpan(['lg' => 2]),
                                        Infolists\Components\Group::make()
                                            ->schema([
                                                Infolists\Components\Fieldset::make('Attendance/Point of Sale')
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('barcode')
                                                            ->placeholder('—')
                                                            ->label('Badge ID')
                                                            ->icon('heroicon-o-qr-code'),
                                                        Infolists\Components\TextEntry::make('pin')
                                                            ->placeholder('—')
                                                            ->label('PIN'),
                                                    ])
                                                    ->columns(1),
                                            ])
                                            ->columnSpan(['lg' => 1]),
                                    ])
                                    ->columns(3),

                            ]),
                    ])
                    ->columnSpan('full'),
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
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Country Name')
                                ->required(),
                            Forms\Components\TextInput::make('code')
                                ->label('Code')
                                ->required()
                                ->rules('max:2'),
                            Forms\Components\Toggle::make('state_required')
                                ->label('State Required')
                                ->required(),
                            Forms\Components\Toggle::make('zip_required')
                                ->label('Zip Required')
                                ->required(),
                        ])
                        ->createOptionAction(
                            fn (Action $action) => $action
                                ->modalHeading('Create Country')
                                ->modalSubmitActionLabel('Create Country')
                                ->modalWidth('lg')
                        )
                        ->afterStateUpdated(fn (Set $set) => $set('state_id', null))
                        ->searchable()
                        ->preload()
                        ->live(),
                    Forms\Components\Select::make('state_id')
                        ->label('State')
                        ->options(
                            fn (Get $get) => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id')
                        )
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
                            fn (Action $action) => $action
                                ->modalHeading('Create State')
                                ->modalSubmitActionLabel('Create State')
                                ->modalWidth('lg')
                        )
                        ->searchable()
                        ->preload()
                        ->required(fn (Get $get) => Country::find($get('country_id'))?->state_required),
                    Forms\Components\Hidden::make('creator_id')
                        ->default(fn () => Auth::user()->id),
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
