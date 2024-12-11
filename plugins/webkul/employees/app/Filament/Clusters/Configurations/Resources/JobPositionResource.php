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
use Webkul\Security\Models\Company as ModelsCompany;
use Webkul\Security\Models\User;

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
                                            ->hintAction(
                                                Action::make('help')
                                                    ->icon('heroicon-o-question-mark-circle')
                                                    ->extraAttributes(['class' => 'text-gray-500'])
                                                    ->hiddenLabel()
                                                    ->tooltip('Enter the official job position title')
                                            ),
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
                                                            ->options(fn () => ModelsCompany::pluck('name', 'id'))
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
                                        Forms\Components\Toggle::make('status')
                                            ->label('Active Status')
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
            ->columns([
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
                Tables\Columns\TextColumn::make('open_date')
                    ->label('Opened Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_employees')
                    ->label('Expected Employees')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_of_employees')
                    ->label('Current Employees')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')
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
            ])
            ->columnToggleFormColumns(2)
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name')
                    ->label('Department'),
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('Company'),
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Status'),
            ])
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
                Tables\Grouping\Group::make('open_date')
                    ->label('Open Date')
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
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sequence');
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
            'index'  => Pages\ListJobPositions::route('/'),
            'create' => Pages\CreateJobPosition::route('/create'),
            'view'   => Pages\ViewJobPosition::route('/{record}'),
            'edit'   => Pages\EditJobPosition::route('/{record}/edit'),
        ];
    }
}
