<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                Forms\Components\ColorPicker::make('color')
                    ->label('Color'),
                Forms\Components\Section::make('Additional Information')
                    ->visible(! empty($customFormFields = static::getCustomFormFields()))
                    ->description('Additional information about this work schedule')
                    ->schema($customFormFields),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->searchable()
                    ->label('Color')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
            ]))
            ->filters(static::mergeCustomTableFilters([]))
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeCategories::route('/'),
        ];
    }
}
