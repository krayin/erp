<?php

namespace Webkul\TimeOff\Filament\Clusters\Reporting\Resources;

use Webkul\TimeOff\Filament\Clusters\Reporting;
use Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource;
use Webkul\TimeOff\Models\Leave;

class ByEmployeeResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $cluster = Reporting::class;

    protected static ?string $modelLabel = 'By Employee';

    public static function form(Form $form): Form
    {
        return TimeOffResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return TimeOffResource::table($table)
            ->defaultGroup('employee.name');
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
