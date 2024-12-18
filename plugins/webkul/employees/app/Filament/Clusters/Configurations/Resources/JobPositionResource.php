<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;
use Webkul\Employee\Models\EmployeeJobPosition;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class JobPositionResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = EmployeeJobPosition::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Recruitment';

    protected static ?string $cluster = Configurations::class;

    public static function getModelLabel(): string
    {
        return 'Job Position';
    }

    public static function getNavigationLabel(): string
    {
        return 'Job Positions';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                                                            ->options(fn () => Company::pluck('name', 'id'))
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
                                        Forms\Components\Select::make('employment_type_id')
                                            ->label('Employment Type')
                                            ->relationship('employmentType', 'name')
                                            ->searchable()
                                            ->preload(),
                                    ])->columns(2),
                                Forms\Components\Section::make('Job Description')
                                    ->schema([
                                        Forms\Components\RichEditor::make('description')
                                            ->label('Job Description')
                                            ->columnSpanFull(),
                                        Forms\Components\RichEditor::make('requirements')
                                            ->label('Job Requirements')
                                            ->columnSpanFull(),
                                    ]),
                                Forms\Components\Section::make('Additional Information')
                                    ->visible(! empty($customFormFields = static::getCustomFormFields()))
                                    ->description('Additional information about this work schedule')
                                    ->schema($customFormFields)
                                    ->columns(),
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
                                        Forms\Components\TextInput::make('no_of_employee')
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
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Status')
                                            ->default(true),
                                    ])->columns(2),
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
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Job Position')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_employees')
                    ->label('Expected Employees')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_of_employee')
                    ->label('Current Employees')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->columnToggleFormColumns(2)
            ->filters(static::mergeCustomTableFilters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name')
                    ->label('Department'),
                Tables\Filters\SelectFilter::make('employmentType')
                    ->relationship('employmentType', 'name')
                    ->label('Employment Type'),
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('Company'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ]))
            ->filtersFormColumns(2)
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Job Position')
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label('Company')
                    ->collapsible(),
                Tables\Grouping\Group::make('department.name')
                    ->label('Department')
                    ->collapsible(),
                Tables\Grouping\Group::make('employmentType.name')
                    ->label('Employment Type')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label('Update At')
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->reorderable('sort');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJobPositions::route('/'),
            'create' => Pages\CreateJobPosition::route('/create'),
            'view'   => Pages\ViewJobPosition::route('/{record}'),
            'edit'   => Pages\EditJobPosition::route('/{record}/edit'),
        ];
    }
}
