<?php

namespace Webkul\Employee\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Resources\DepartmentResource\Pages;
use Webkul\Employee\Models\Department;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Support\Models\Company;

class DepartmentResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Employees';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('General Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),
                                        Forms\Components\Select::make('manager_id')
                                            ->label('Manager')
                                            ->relationship('manager', 'name')
                                            ->searchable()
                                            ->preload()
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
                                    ])
                                    ->columns(2),
                                Forms\Components\Section::make('Additional Information')
                                    ->visible(! empty($customFormFields = static::getCustomFormFields()))
                                    ->description('Additional information about this work schedule')
                                    ->schema($customFormFields),
                            ]),
                    ]),
            ])
            ->columns('full');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make('General Information')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->label('Name'),
                                        Infolists\Components\TextEntry::make('manager.name')
                                            ->label('Manager'),
                                        Infolists\Components\TextEntry::make('company.name')
                                            ->label('Company'),
                                        Infolists\Components\ColorEntry::make('color')
                                            ->label('Color'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('manager.partner.avatar')
                        ->height(150)
                        ->width(200),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('manager.name')
                                ->icon('heroicon-m-briefcase')
                                ->sortable()
                                ->searchable()
                                ->label('Job Title'),
                        ])
                            ->visible(fn ($record) => filled($record?->manager?->name)),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('company.name')
                                ->searchable()
                                ->icon('heroicon-m-building-office-2')
                                ->searchable()
                                ->label('Job Title'),
                        ])
                            ->visible(fn ($record) => filled($record?->company?->name)),
                    ])->space(1),
                ])->space(4),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
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
            ->filtersFormColumns(2)
            ->filters(static::mergeCustomTableFilters([
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label('Name')
                            ->icon('heroicon-o-building-office-2'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('manager.name')
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
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at'),
                    ]),
            ]))
            ->paginated([
                18,
                36,
                72,
                'all',
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function () {
                            Notification::make()
                                ->title('Delete action executed.')
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

    public static function getSlug(): string
    {
        return 'employees/departments';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'view'   => Pages\ViewDepartment::route('/{record}'),
            'edit'   => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
