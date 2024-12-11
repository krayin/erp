<?php

namespace Webkul\Employee\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Resources\DepartmentResource\Pages;
use Webkul\Employee\Models\Department;
use Webkul\Security\Models\Company;
use Webkul\Security\Models\User;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Employees';

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
                                    ])
                                    ->columns(2),
                            ]),
                    ]),
            ])
            ->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('manager.image')
                        ->defaultImageUrl(fn ($record): string => 'https://ui-avatars.com/api/?name='.$record->name)
                        ->label('Manager Photo')
                        ->height('100%')
                        ->width('100%'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->label('Department Name')
                            ->weight('bold'),
                        Tables\Columns\TextColumn::make('manager.name')
                            ->label('Manager')
                            ->color('gray'),
                        Tables\Columns\TextColumn::make('company.name')
                            ->label('Company')
                            ->color('blue')
                            ->limit(30),
                    ]),
                ])->space(3),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\ColorColumn::make('color')
                            ->label('Color')
                            ->grow(false),

                        Tables\Columns\TextColumn::make('description')
                            ->label('Description')
                            ->color('gray'),
                    ]),
                ])->collapsible(),
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
            ->filters([])
            ->contentGrid([
                'xl'  => 2,
                '2xl' => 2,
            ])
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
