<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;
use Webkul\Employee\Models\EmploymentType;
use Webkul\Fields\Filament\Traits\HasCustomFields;

class EmploymentTypeResource extends Resource
{
    use HasCustomFields;

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
                Forms\Components\Hidden::make('creator_id')
                    ->default(Auth::user()->id),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('code')
                    ->label('Code'),
                Forms\Components\Select::make('country_id')
                    ->searchable()
                    ->preload()
                    ->relationship('country', 'name'),
                Forms\Components\Section::make('Additional Information')
                    ->visible(! empty($customFormFields = static::getCustomFormFields()))
                    ->description('Additional information about this work schedule')
                    ->schema($customFormFields)
                    ->columns(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label('Code'),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->searchable()
                    ->label('Country'),
            ]))
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('code')
                    ->label('Code')
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label('Created By')
                    ->collapsible(),
                Tables\Grouping\Group::make('country.name')
                    ->label('Country')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label('Update At')
                    ->date()
                    ->collapsible(),
            ])
            ->filters(static::mergeCustomTableFilters([]))
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sort'] = EmploymentType::max('sort') + 1;

                        $data['code'] = $data['code'] ?? $data['name'];

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEmploymentTypes::route('/'),
        ];
    }
}
