<?php

namespace Webkul\Employee\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Enums\Gender;
use Webkul\Employee\Enums\MaritalStatus;
use Webkul\Employee\Filament\Resources\EmployeeResource\Pages;
use Webkul\Employee\Models\Employee;
use Webkul\Fields\Filament\Traits\HasCustomFields;

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
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Personal Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Full Name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Select::make('gender')
                                            ->label('Gender')
                                            ->searchable()
                                            ->preload()
                                            ->options(Gender::options()),
                                        Forms\Components\DatePicker::make('birthday')
                                            ->label('Date of Birth')
                                            ->native(false)
                                            ->maxDate(now()),
                                        Forms\Components\Select::make('marital')
                                            ->label('Marital Status')
                                            ->searchable()
                                            ->preload()
                                            ->options(MaritalStatus::options()),
                                        Forms\Components\TextInput::make('spouse_complete_name')
                                            ->label('Spouse Name'),
                                        Forms\Components\DatePicker::make('spouse_birthdate')
                                            ->label('Spouse Birthdate')
                                            ->native(false),
                                        Forms\Components\TextInput::make('place_of_birth')
                                            ->label('Place of Birth'),
                                        Forms\Components\Select::make('country_of_birth')
                                            ->relationship('countryOfBirth', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Country of Birth'),
                                        Forms\Components\TextInput::make('children')
                                            ->label('Number of Children')
                                            ->numeric()
                                            ->minValue(0),
                                    ])->columns(2),
                                Forms\Components\Section::make('Employment Details')
                                    ->schema([
                                        Forms\Components\Select::make('company_id')
                                            ->relationship('company', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Company'),
                                        Forms\Components\Select::make('department_id')
                                            ->relationship('department', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Department'),
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
                                        Forms\Components\TextInput::make('mobile_phone')
                                            ->label('Mobile Phone')
                                            ->tel(),
                                        Forms\Components\TextInput::make('work_email')
                                            ->label('Work Email')
                                            ->email(),
                                        Forms\Components\ColorPicker::make('color')
                                            ->label('Color'),
                                    ])->columns(2),
                                Forms\Components\Section::make('Identification')
                                    ->schema([
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
                                Forms\Components\Section::make('Educational Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('certificate')
                                            ->label('Certificate'),
                                        Forms\Components\TextInput::make('study_field')
                                            ->label('Field of Study'),
                                        Forms\Components\TextInput::make('study_school')
                                            ->label('School'),
                                    ])->columns(2),
                                Forms\Components\Section::make('Contact Information')
                                    ->schema([
                                        Forms\Components\Select::make('country_id')
                                            ->relationship('country', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Country'),
                                        Forms\Components\Select::make('private_country_id')
                                            ->relationship('privateCountry', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Private Country'),
                                        Forms\Components\Select::make('private_state_id')
                                            ->relationship('privateState', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Private State'),
                                        Forms\Components\TextInput::make('private_street1')
                                            ->label('Private Street 1'),
                                        Forms\Components\TextInput::make('private_street2')
                                            ->label('Private Street 2'),
                                        Forms\Components\TextInput::make('private_city')
                                            ->label('Private City'),
                                        Forms\Components\TextInput::make('private_zip')
                                            ->label('Private Zip'),
                                        Forms\Components\TextInput::make('private_phone')
                                            ->label('Private Phone')
                                            ->tel(),
                                        Forms\Components\TextInput::make('private_email')
                                            ->label('Private Email')
                                            ->email(),
                                    ])->columns(2),
                                Forms\Components\Section::make('Additional Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('lang')
                                            ->label('Language'),
                                        Forms\Components\TextInput::make('barcode')
                                            ->label('Barcode'),
                                        Forms\Components\TextInput::make('pin')
                                            ->label('PIN'),
                                        Forms\Components\TextInput::make('private_car_plate')
                                            ->label('Private Car Plate'),
                                        Forms\Components\KeyValue::make('employee_properties')
                                            ->label('Employee Properties'),
                                        Forms\Components\Textarea::make('additional_note')
                                            ->label('Additional Notes'),
                                        Forms\Components\Textarea::make('notes')
                                            ->label('Notes'),
                                        ...static::getCustomFormFields(),
                                    ])->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Tags')
                                    ->schema([

                                    ]),
                                Forms\Components\Section::make('Employment Status')
                                    ->schema([
                                        Forms\Components\Select::make('employee_employee_categories')
                                            ->multiple()
                                            ->relationship('categories', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Tags'),
                                        Forms\Components\Select::make('user_id')
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Related User'),
                                        Forms\Components\Select::make('job_id')
                                            ->relationship('job', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Job Position'),
                                        Forms\Components\Select::make('work_location_id')
                                            ->relationship('workLocation', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Work Location'),
                                        Forms\Components\Select::make('partner_id')
                                            ->relationship('partner', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Partner'),
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
                                        Forms\Components\Select::make('bank_account_id')
                                            ->relationship('bankAccount', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Bank Account'),
                                        Forms\Components\TextInput::make('distance_home_work')
                                            ->label('Distance Home to Work')
                                            ->numeric(),
                                        Forms\Components\TextInput::make('km_home_work')
                                            ->label('KM Home to Work')
                                            ->numeric(),
                                        Forms\Components\TextInput::make('distance_home_work_unit')
                                            ->label('Distance Unit'),
                                    ])->columns(1),
                                Forms\Components\Section::make('Status')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active Employee')
                                            ->default(true),
                                        Forms\Components\Toggle::make('is_flexible')
                                            ->label('Flexible Work'),
                                        Forms\Components\Toggle::make('is_fully_flexible')
                                            ->label('Fully Flexible'),
                                        Forms\Components\Toggle::make('work_permit_scheduled_activity')
                                            ->label('Work Permit Scheduled Activity'),
                                        Forms\Components\Select::make('departure_reason_id')
                                            ->relationship('departureReason', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Departure Reason'),
                                        Forms\Components\DatePicker::make('departure_date')
                                            ->label('Departure Date')
                                            ->native(false),
                                        Forms\Components\Textarea::make('departure_description')
                                            ->label('Departure Description'),
                                    ])->columns(1),
                                Forms\Components\Section::make('Status')
                                    ->schema([
                                        Forms\Components\TextInput::make('emergency_contact')
                                            ->label('Emergency Contact Name'),
                                        Forms\Components\TextInput::make('emergency_phone')
                                            ->label('Emergency Contact Phone')
                                            ->tel(),
                                    ])->columns(1),
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
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image')
                        ->defaultImageUrl(fn ($record): string => 'https://demo.filamentphp.com/storage/0b023058-c3c0-418c-86c9-aa35bdfb3b1b.jpg')
                        ->height('100%')
                        ->width('100%'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('job_title')
                            ->label('Job Title')
                            ->color('gray'),
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
                ])->space(3),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\ColorColumn::make('color')
                            ->grow(false),
                        Tables\Columns\TextColumn::make('department.name')
                            ->label('Department')
                            ->icon('heroicon-o-building-office')
                            ->color('gray')
                            ->sortable(),
                    ]),
                ])->collapsible(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
