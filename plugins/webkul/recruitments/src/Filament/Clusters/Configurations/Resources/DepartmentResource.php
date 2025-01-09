<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources;

use Filament\Tables\Table;
use Webkul\Recruitment\Filament\Clusters\Configurations;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;
use Webkul\Employee\Filament\Resources\DepartmentResource as BaseDepartmentResource;

class DepartmentResource extends BaseDepartmentResource
{
    protected static ?string $cluster = Configurations::class;

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/configurations/resources/department.navigation.group');
    }

    public static function getSlug(): string
    {
        return 'departments';
    }

    public static function table(Table $table): Table
    {
        return BaseDepartmentResource::table($table)
            ->contentGrid([
                'md' => 2,
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
