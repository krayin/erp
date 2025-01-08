<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources;

use Webkul\Recruitment\Filament\Clusters\Configurations;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Models\EmploymentType;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource as BaseEmploymentTypeResource;

class EmploymentTypeResource extends Resource
{
    protected static ?string $model = EmploymentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Configurations::class;

    public static function getModelLabel(): string
    {
        return BaseEmploymentTypeResource::getModelLabel();
    }

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/configurations/resources/employment-type.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return BaseEmploymentTypeResource::getNavigationLabel();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return BaseEmploymentTypeResource::getGloballySearchableAttributes();
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return BaseEmploymentTypeResource::getGlobalSearchResultDetails($record);
    }

    public static function form(Form $form): Form
    {
        return BaseEmploymentTypeResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return BaseEmploymentTypeResource::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmploymentTypes::route('/'),
        ];
    }
}
