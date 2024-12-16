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
use Webkul\Employee\Filament\Resources\EmployeeResource\Pages;
use Webkul\Employee\Filament\Resources\EmployeeResource\RelationManagers;
use Webkul\Employee\Models\Employee;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Security\Enums\PermissionType;
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
                Forms\Components\Tabs::make('Employee Information')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Work Information')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Section::make('Personal Details')
                                                    ->description('Basic personal information')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('name')
                                                            ->label('Full Name')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->columnSpan(2),
                                                        Forms\Components\Select::make('gender')
                                                            ->label('Gender')
                                                            ->searchable()
                                                            ->preload()
                                                            ->options(Gender::options()),
                                                        Forms\Components\DatePicker::make('birthday')
                                                            ->label('Date of Birth')
                                                            ->native(false)
                                                            ->maxDate(now()),
                                                        Forms\Components\TextInput::make('place_of_birth')
                                                            ->label('Place of Birth'),
                                                        Forms\Components\Select::make('country_of_birth')
                                                            ->relationship('countryOfBirth', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Country of Birth'),
                                                    ])->columns(2),
                                                Forms\Components\Section::make('Employment Information')
                                                    ->description('Company and job-related details')
                                                    ->schema([
                                                        Forms\Components\Select::make('user_id')
                                                            ->relationship('user', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Related User')
                                                            ->createOptionForm(static::getCreateUserFormSchema())
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
                                                        Forms\Components\Select::make('job_id')
                                                            ->relationship('job', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Job Position')
                                                            ->createOptionForm(static::getCreateJobPositionSchema())
                                                            ->createOptionAction(
                                                                fn(Action $action) => $action
                                                                    ->modalHeading('Create User')
                                                                    ->modalSubmitActionLabel('Create User')
                                                            ),
                                                        Forms\Components\Select::make('parent_id')
                                                            ->relationship('parent', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Reporting Manager'),
                                                        Forms\Components\Select::make('coach_id')
                                                            ->searchable()
                                                            ->preload()
                                                            ->relationship('coach', 'name')
                                                            ->label('Coach/Mentor'),
                                                        Forms\Components\TextInput::make('distance_home_work')
                                                            ->label('Distance Home to Work')
                                                            ->numeric()
                                                            ->suffix('km'),
                                                        Forms\Components\TextInput::make('km_home_work')
                                                            ->label('KM Home to Work')
                                                            ->numeric()
                                                            ->suffix('km'),
                                                        Forms\Components\Select::make('work_location_id')
                                                            ->relationship('workLocation', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Work Location'),
                                                        Forms\Components\TextInput::make('distance_home_work_unit')
                                                            ->label('Distance Unit'),
                                                        Forms\Components\Select::make('employees_employee_categories')
                                                            ->multiple()
                                                            ->relationship('categories', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Employee Tags')
                                                            ->helperText('Select relevant tags for this employee'),

                                                    ])->columns(2),
                                            ])
                                            ->columnSpan(['lg' => 2]),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\Section::make('Avatar')
                                                            ->schema([
                                                                Forms\Components\FileUpload::make('avatar')
                                                                    ->hiddenLabel()
                                                                    ->image()
                                                                    ->directory('employees/logo')
                                                                    ->visibility('private'),
                                                            ]),
                                                    ])
                                                    ->columnSpan(['lg' => 1]),
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\Section::make('Organizational Details')
                                                            ->description('Reporting structure and additional information')
                                                            ->schema([
                                                                Forms\Components\Select::make('company_id')
                                                                    ->relationship('company', 'name')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->label('Company'),
                                                                Forms\Components\Select::make('department_id')
                                                                    ->label('Department')
                                                                    ->relationship(name: 'department', titleAttribute: 'name')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->createOptionForm([
                                                                        Forms\Components\Group::make()
                                                                            ->schema([
                                                                                Forms\Components\TextInput::make('name')
                                                                                    ->label('Name')
                                                                                    ->required()
                                                                                    ->maxLength(255)
                                                                                    ->live(onBlur: true),
                                                                                Forms\Components\Select::make('manager_id')
                                                                                    ->label('Manager')
                                                                                    ->relationship('manager', 'name')
                                                                                    ->options(function () {
                                                                                        return User::whereHas('roles', function ($query) {
                                                                                            $query->where('name', 'admin');
                                                                                        })->pluck('name', 'id');
                                                                                    })
                                                                                    ->searchable()
                                                                                    ->placeholder('Select a manager')
                                                                                    ->nullable(),
                                                                                Forms\Components\Select::make('company_id')
                                                                                    ->label('Company')
                                                                                    ->relationship('company', 'name')
                                                                                    ->options(fn() => Company::pluck('name', 'id'))
                                                                                    ->searchable()
                                                                                    ->placeholder('Select a Company')
                                                                                    ->nullable(),
                                                                                Forms\Components\ColorPicker::make('color')
                                                                                    ->label('Color'),
                                                                            ])->columns(2),
                                                                    ])
                                                                    ->createOptionAction(function (Action $action) {
                                                                        return $action
                                                                            ->modalHeading('Create Department')
                                                                            ->modalSubmitActionLabel('Create Department')
                                                                            ->modalWidth('2xl');
                                                                    }),
                                                                Forms\Components\TextInput::make('job_title')
                                                                    ->label('Job Title')
                                                                    ->maxLength(255),
                                                                Forms\Components\Select::make('employee_type')
                                                                    ->relationship('employmentType', 'name')
                                                                    ->searchable()
                                                                    ->preload()
                                                                    ->label('Employment Type')
                                                                    ->required(),
                                                                Forms\Components\TextInput::make('work_phone')
                                                                    ->label('Work Phone')
                                                                    ->tel(),
                                                                Forms\Components\TextInput::make('work_email')
                                                                    ->label('Work Email')
                                                                    ->email(),
                                                                Forms\Components\TextInput::make('mobile_phone')
                                                                    ->label('Mobile Phone')
                                                                    ->tel(),
                                                                Forms\Components\ColorPicker::make('color')
                                                                    ->label('Color'),
                                                            ]),
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
                                                Forms\Components\Section::make('Personal Background')
                                                    ->description('Marital and family information')
                                                    ->schema([
                                                        Forms\Components\Section::make('Personal Background')
                                                            ->description('Marital and family information')
                                                            ->schema([
                                                                Forms\Components\Select::make('marital')
                                                                    ->label('Marital Status')
                                                                    ->searchable()
                                                                    ->preload()
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
                                                    ])->columns(2),
                                                Forms\Components\Section::make('Educational Information')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('certificate')
                                                            ->label('Certificate'),
                                                        Forms\Components\TextInput::make('study_field')
                                                            ->label('Field of Study'),
                                                        Forms\Components\TextInput::make('study_school')
                                                            ->label('School'),
                                                    ])->columns(2),
                                                Forms\Components\Section::make('Contact Details')
                                                    ->description('Private address and contact information')
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
                                                            ->live()
                                                            ->required(),
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
                                                            ->tel(),
                                                        Forms\Components\TextInput::make('private_email')
                                                            ->label('Private Email')
                                                            ->email(),
                                                    ])->columns(2),
                                                Forms\Components\Section::make('Identification')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('private_car_plate')
                                                            ->label('Private Car Plate'),
                                                        Forms\Components\TextInput::make('ssnid')
                                                            ->label('Social Security Number'),
                                                        Forms\Components\TextInput::make('sinid')
                                                            ->label('SIN ID'),
                                                        Forms\Components\TextInput::make('identification_id')
                                                            ->label('Identification ID'),
                                                        Forms\Components\TextInput::make('passport_id')
                                                            ->label('Passport Number'),
                                                        Forms\Components\TextInput::make('permit_no')
                                                            ->label('Permit Number'),
                                                        Forms\Components\TextInput::make('visa_no')
                                                            ->label('Visa Number'),
                                                        Forms\Components\DatePicker::make('visa_expire')
                                                            ->label('Visa Expiration')
                                                            ->native(false),
                                                        Forms\Components\DatePicker::make('work_permit_expiration_date')
                                                            ->label('Work Permit Expiration')
                                                            ->native(false),
                                                    ])->columns(2),
                                            ])
                                            ->columnSpan(['lg' => 2]),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Section::make('Emergency Contact')
                                                    ->description('Person to contact in case of emergency')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('emergency_contact')
                                                            ->label('Contact Name')
                                                            ->required(),
                                                        Forms\Components\TextInput::make('emergency_phone')
                                                            ->label('Contact Phone')
                                                            ->tel()
                                                            ->required(),
                                                    ])->columns(2),
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
                                                Forms\Components\Section::make('Employment Status')
                                                    ->description('Current employment conditions')
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
                                                        Forms\Components\Select::make('departure_reason_id')
                                                            ->relationship('departureReason', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->label('Departure Reason')
                                                            ->createOptionForm([
                                                                Forms\Components\TextInput::make('name')
                                                                    ->label('Departure Name')
                                                                    ->required(),
                                                            ])
                                                            ->createOptionAction(
                                                                fn(Action $action) => $action
                                                                    ->modalHeading('Create Departure')
                                                                    ->modalSubmitActionLabel('Create Departure')
                                                                    ->modalWidth('lg')
                                                            ),
                                                        Forms\Components\DatePicker::make('departure_date')
                                                            ->label('Departure Date')
                                                            ->native(false),
                                                        Forms\Components\Textarea::make('departure_description')
                                                            ->label('Departure Description'),
                                                    ])->columns(2),
                                                Forms\Components\Section::make('Additional Information')
                                                    ->description('Supplementary employee details')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('lang')
                                                            ->label('Primary Language'),
                                                        Forms\Components\TextInput::make('barcode')
                                                            ->label('Employee Barcode'),
                                                        Forms\Components\TextInput::make('pin')
                                                            ->label('Personal Identification Number'),

                                                        Forms\Components\Textarea::make('additional_note')
                                                            ->label('Additional Notes')
                                                            ->rows(3),
                                                        Forms\Components\Textarea::make('notes')
                                                            ->label('Notes'),
                                                    ])->columns(2),
                                            ])
                                            ->columnSpan(['lg' => 2]),

                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Section::make('System Settings')
                                                    ->description('Internal system configurations')
                                                    ->schema([
                                                        Forms\Components\KeyValue::make('employee_properties')
                                                            ->label('Employee Properties')
                                                            ->helperText('Key-value pairs for additional metadata'),
                                                        ...static::getCustomFormFields(),
                                                    ]),
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
                        ->height(250)
                        ->width(300),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('job_title')
                            ->label('Job Title')
                            ->color('gray'),
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\Layout\Split::make([
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
                            ]),
                        ]),
                        Tables\Columns\TextColumn::make('categories.name')
                            ->badge(),
                    ])->space(1),
                ])->space(4),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
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
                    ->color('primary')
                    ->outlined(),
                Tables\Actions\EditAction::make()
                    ->color('success')
                    ->outlined(),
                Tables\Actions\RestoreAction::make()
                    ->color('gray')
                    ->outlined(),
                Tables\Actions\DeleteAction::make()
                    ->color('danger')
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

    public static function getCreateUserFormSchema(): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make(__('security::app.filament.resources.user.form.sections.general.title'))
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label(__('security::app.filament.resources.user.form.sections.general.fields.name'))
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true),
                                    Forms\Components\TextInput::make('email')
                                        ->label(__('security::app.filament.resources.user.form.sections.general.fields.email'))
                                        ->email()
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('password')
                                        ->label(__('security::app.filament.resources.user.form.sections.general.fields.password'))
                                        ->password()
                                        ->required()
                                        ->hiddenOn('edit')
                                        ->maxLength(255)
                                        ->rule('min:8'),
                                    Forms\Components\TextInput::make('password_confirmation')
                                        ->label(__('security::app.filament.resources.user.form.sections.general.fields.password-confirmation'))
                                        ->password()
                                        ->hiddenOn('edit')
                                        ->rule('required', fn($get) => (bool) $get('password'))
                                        ->same('password'),
                                ])
                                ->columns(2),
                            Forms\Components\Section::make(__('security::app.filament.resources.user.form.sections.permissions.title'))
                                ->schema([
                                    Forms\Components\Select::make('roles')
                                        ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.roles'))
                                        ->relationship('roles', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->searchable(),
                                    Forms\Components\Select::make('resource_permission')
                                        ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.resource-permission'))
                                        ->options(PermissionType::options())
                                        ->required()
                                        ->preload()
                                        ->searchable(),
                                    Forms\Components\Select::make('teams')
                                        ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.teams'))
                                        ->relationship('teams', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->searchable(),
                                ])
                                ->columns(2),
                        ])
                        ->columnSpan(['lg' => 2]),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make(__('security::app.filament.resources.user.form.sections.avatar.title'))
                                ->schema([
                                    Forms\Components\FileUpload::make('avatar')
                                        ->hiddenLabel()
                                        ->image()
                                        ->directory('users/avatars')
                                        ->visibility('private'),
                                ])
                                ->columns(1),
                            Forms\Components\Section::make('Language & Status')
                                ->schema([
                                    Forms\Components\Select::make('language')
                                        ->label(__('Preferred Language'))
                                        ->options([
                                            'en' => __('English'),
                                        ])
                                        ->searchable(),
                                    Forms\Components\Toggle::make('is_active')
                                        ->label(__('Active Status'))
                                        ->default(true),
                                ])
                                ->columns(1),
                            Forms\Components\Section::make('Multi Company')
                                ->schema([
                                    Forms\Components\Select::make('allowed_companies')
                                        ->label(__('Allowed Companies'))
                                        ->relationship('allowedCompanies', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->searchable(),
                                    Forms\Components\Select::make('default_company_id')
                                        ->label(__('Default Company'))
                                        ->relationship('defaultCompany', 'name')
                                        ->searchable()
                                        ->preload(),
                                ]),
                        ])
                        ->columnSpan(['lg' => 1]),
                ])
                ->columns(3),
        ];
    }

    public static function getCreateJobPositionSchema(): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('Employment Information')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Job Position Title')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->hintIcon('heroicon-o-question-mark-circle', tooltip: 'Enter the official job position title'),
                                    Forms\Components\Select::make('department_id')
                                        ->label('Department')
                                        ->relationship(name: 'department', titleAttribute: 'name')
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            Forms\Components\Group::make()
                                                ->schema([
                                                    Forms\Components\TextInput::make('name')
                                                        ->label('Name')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->live(onBlur: true),
                                                    Forms\Components\Select::make('manager_id')
                                                        ->label('Manager')
                                                        ->relationship('manager', 'name')
                                                        ->options(function () {
                                                            return User::whereHas('roles', function ($query) {
                                                                $query->where('name', 'admin');
                                                            })->pluck('name', 'id');
                                                        })
                                                        ->searchable()
                                                        ->placeholder('Select a manager')
                                                        ->nullable(),
                                                    Forms\Components\Select::make('company_id')
                                                        ->label('Company')
                                                        ->relationship('company', 'name')
                                                        ->options(fn() => Company::pluck('name', 'id'))
                                                        ->searchable()
                                                        ->placeholder('Select a Company')
                                                        ->nullable(),
                                                    Forms\Components\ColorPicker::make('color')
                                                        ->label('Color'),
                                                ])->columns(2),
                                        ])
                                        ->createOptionAction(function (Action $action) {
                                            return $action
                                                ->modalHeading('Create Department')
                                                ->modalSubmitActionLabel('Create Department')
                                                ->modalWidth('2xl');
                                        }),
                                    Forms\Components\Select::make('company_id')
                                        ->label('Company')
                                        ->relationship('company', 'name')
                                        ->searchable()
                                        ->preload(),
                                ]),
                            Forms\Components\Section::make('Job Description')
                                ->schema([
                                    Forms\Components\RichEditor::make('description')
                                        ->label('Job Description')
                                        ->columnSpanFull(),
                                    Forms\Components\RichEditor::make('requirements')
                                        ->label('Job Requirements')
                                        ->columnSpanFull(),
                                ]),
                        ])
                        ->columnSpan(['lg' => 2]),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('Workforce Planning')
                                ->schema([
                                    Forms\Components\TextInput::make('expected_employees')
                                        ->label('Expected Employees')
                                        ->numeric()
                                        ->minValue(0)
                                        ->default(0),
                                    Forms\Components\TextInput::make('no_of_employees')
                                        ->label('Current Employees')
                                        ->numeric()
                                        ->minValue(0)
                                        ->default(0),
                                    Forms\Components\TextInput::make('no_of_recruitment')
                                        ->label('Recruitment Target')
                                        ->numeric()
                                        ->minValue(0)
                                        ->default(0),
                                ])->columns(2),
                            Forms\Components\Section::make('Position Status')
                                ->schema([
                                    Forms\Components\DatePicker::make('open_date')
                                        ->label('Position Opened Date')
                                        ->default(now())
                                        ->native(false),
                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Status')
                                        ->default(true),
                                ])->columns(2),
                        ])
                        ->columnSpan(['lg' => 1]),
                ])
                ->columns(3),
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
