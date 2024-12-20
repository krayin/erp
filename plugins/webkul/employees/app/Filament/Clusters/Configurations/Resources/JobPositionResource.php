<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;
use Webkul\Employee\Models\EmployeeJobPosition;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class JobPositionResource extends Resource
{
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(['default' => 3])
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make('Employment Information')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->icon('heroicon-o-briefcase')
                                            ->placeholder('—')
                                            ->label('Job Position Title'),
                                        Infolists\Components\TextEntry::make('department.name')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-building-office')
                                            ->label('Department'),
                                        Infolists\Components\TextEntry::make('company.name')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-building-office-2')
                                            ->label('Company'),
                                        Infolists\Components\TextEntry::make('employmentType.name')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-briefcase')
                                            ->label('Employment Type'),
                                    ])->columns(2),
                                Infolists\Components\Section::make('Job Description')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('description')
                                            ->label('Job Description')
                                            ->placeholder('—')
                                            ->columnSpanFull(),
                                        Infolists\Components\TextEntry::make('requirements')
                                            ->label('Job Requirements')
                                            ->placeholder('—')
                                            ->columnSpanFull(),
                                    ]),
                            ])->columnSpan(2),
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Workforce Planning')
                                ->schema([
                                    Infolists\Components\TextEntry::make('expected_employees')
                                        ->label('Expected Employees')
                                        ->placeholder('—')
                                        ->icon('heroicon-o-user-group')
                                        ->numeric(),
                                    Infolists\Components\TextEntry::make('no_of_employee')
                                        ->icon('heroicon-o-user-group')
                                        ->placeholder('—')
                                        ->label('Current Employees')
                                        ->numeric(),
                                    Infolists\Components\TextEntry::make('no_of_recruitment')
                                        ->icon('heroicon-o-user-group')
                                        ->placeholder('—')
                                        ->label('Recruitment Target')
                                        ->numeric(),
                                ]),
                            Infolists\Components\Section::make('Position Status')
                                ->schema([
                                    Infolists\Components\IconEntry::make('is_active')
                                        ->label('Status'),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->columnToggleFormColumns(2)
            ->filters([
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
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label('Name')
                            ->icon('heroicon-o-building-office-2'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label('Company')
                            ->icon('heroicon-o-building-office')
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
                            ->icon('heroicon-o-building-office-2')
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
                            ->icon('heroicon-o-user')
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
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at'),
                    ]),
            ])
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
