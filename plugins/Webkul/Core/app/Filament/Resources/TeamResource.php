<?php

namespace Webkul\Core\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Core\Filament\Resources\TeamResource\Pages;
use Webkul\Core\Models\Team;
use Webkul\Field\Filament\Traits\HasCustomFields;

class TeamResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Teams';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::mergeCustomFormFields([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
            ]));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
            ]))
            ->filters(self::getCustomTableFilters())
            // ->filters([
            //     \Filament\Tables\Filters\QueryBuilder::make()
            //         ->constraints(static::getTableQueryBuilderConstraints()),
            // ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTeams::route('/'),
        ];
    }
}
