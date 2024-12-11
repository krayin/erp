<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;
use Webkul\Employee\Models\EmploymentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmploymentTypeResource extends Resource
{
    protected static ?string $model = EmploymentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Recruitment';

    public static function getModelLabel(): string
    {
        return 'Employment Type';
    }

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('code')
                    ->label('Code'),
                Forms\Components\Select::make('company_id')
                    ->searchable()
                    ->preload()
                    ->relationship('company', 'name'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label('Code'),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('User'),
                Tables\Columns\TextColumn::make('company.name')
                    ->sortable()
                    ->searchable()
                    ->label('Company'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sequence'] = EmploymentType::max('sequence') + 1;

                        $data['code'] = $data['code'] ?? $data['name'];

                        $data['user_id'] = Auth::user()->id;

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sequence');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmploymentTypes::route('/'),
        ];
    }
}
