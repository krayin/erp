<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources;

use Webkul\Recruitment\Filament\Clusters\Configurations;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Models\Department;
use Webkul\Employee\Filament\Resources\DepartmentResource as BaseDepartmentResource;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $cluster = Configurations::class;

    public static function getModelLabel(): string
    {
        return BaseDepartmentResource::getModelLabel();
    }

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/configurations/resources/department.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return BaseDepartmentResource::getNavigationLabel();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return BaseDepartmentResource::getGloballySearchableAttributes();
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return BaseDepartmentResource::getGlobalSearchResultDetails($record);
    }

    public static function form(Form $form): Form
    {
        return BaseDepartmentResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return BaseDepartmentResource::table($table)
        ->contentGrid([
            'md' => 3,
            'xl' => 3,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
            'view' => Pages\ViewDepartment::route('/{record}'),
        ];
    }
}
