<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Clusters\Employee;
use Webkul\Employee\Filament\Clusters\Employee\Resources\EmploymentTypeResource\Pages;
use Webkul\Employee\Models\EmploymentType;

class EmploymentTypeResource extends Resource
{
    protected static ?string $model = EmploymentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Management';

    public static function getModelLabel(): string
    {
        return 'Employment Type';
    }

    protected static ?string $cluster = Employee::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),
            ])
            ->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmploymentTypes::route('/'),
        ];
    }
}
