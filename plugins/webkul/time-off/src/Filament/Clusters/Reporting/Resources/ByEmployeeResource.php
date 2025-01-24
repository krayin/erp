<?php

namespace Webkul\TimeOff\Filament\Clusters\Reporting\Resources;

use Webkul\TimeOff\Filament\Clusters\Reporting;
use Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\TimeOff\Models\Leave;

class ByEmployeeResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Reporting::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListByEmployees::route('/'),
            'create' => Pages\CreateByEmployee::route('/create'),
            'edit' => Pages\EditByEmployee::route('/{record}/edit'),
        ];
    }
}
