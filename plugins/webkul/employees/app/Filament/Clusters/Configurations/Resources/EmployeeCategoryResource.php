<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Enums;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmployeeCategoryResource\Pages;
use Webkul\Employee\Models\EmployeeCategory;
use Webkul\Fields\Filament\Traits\HasCustomFields;

class EmployeeCategoryResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = EmployeeCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';

    protected static ?string $navigationGroup = 'Employee';

    protected static ?string $cluster = Configurations::class;

    public static function getModelLabel(): string
    {
        return 'Tags';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Enter the name of the tag'),
                Forms\Components\Select::make('color')
                    ->label('Color')
                    ->options(function () {
                        return collect(Enums\Colors::options())->mapWithKeys(function ($value, $key) {
                            return [
                                $key => '<div class="flex items-center gap-4"><span class="flex w-5 h-5 rounded-full" style="background: rgb(var(--'.$key.'-500))"></span> '.$value.'</span>',
                            ];
                        });
                    })
                    ->native(false)
                    ->allowHtml(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable()
                    ->label('Color')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn (EmployeeCategory $employeeCategory) => '<span class="flex w-5 h-5 rounded-full" style="background: rgb(var(--'.$employeeCategory->color.'-500))"></span>')
                    ->html()
                    ->sortable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeCategories::route('/'),
        ];
    }
}
