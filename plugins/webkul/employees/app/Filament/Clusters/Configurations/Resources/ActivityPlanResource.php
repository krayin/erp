<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;
use Webkul\Employee\Models\ActivityPlan;
use Webkul\Employee\Models\Employee;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class ActivityPlanResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = ActivityPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Plan Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('model_type')
                            ->readOnly()
                            ->default(Employee::class),
                        Forms\Components\Select::make('model_id')
                            ->label('Employee')
                            ->options(fn () => Employee::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->default(fn () => Auth::user()->defaultCompany?->id)
                            ->required()
                            ->suffixIcon('heroicon-o-building-office')
                            ->preload(),
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
                        Forms\Components\Toggle::make('active')
                            ->default(true)
                            ->inline(false),
                        Forms\Components\Section::make('Additional Information')
                            ->visible(! empty($customFormFields = static::getCustomFormFields()))
                            ->description('Additional information about this work schedule')
                            ->schema($customFormFields)
                            ->columns(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('model.name')
                    ->label('Related Entity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->filters(static::mergeCustomTableFilters([]))
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListActivityPlans::route('/'),
            'view'   => Pages\ViewActivityPlan::route('/{record}'),
            'edit'   => Pages\EditActivityPlan::route('/{record}/edit'),
        ];
    }
}
