<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources;

use Webkul\Recruitment\Filament\Clusters\Configurations;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Models\SkillType;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource as BaseSkillTypeResource;

class SkillTypeResource extends Resource
{
    protected static ?string $model = SkillType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Configurations::class;

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/configurations/resources/skill-type.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return BaseSkillTypeResource::getNavigationLabel();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return BaseSkillTypeResource::getGloballySearchableAttributes();
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return BaseSkillTypeResource::getGlobalSearchResultDetails($record);
    }

    public static function form(Form $form): Form
    {
        return BaseSkillTypeResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return BaseSkillTypeResource::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return BaseSkillTypeResource::infolist($infolist);
    }

    public static function getRelations(): array
    {
        return BaseSkillTypeResource::getRelations();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSkillTypes::route('/'),
            'view'   => Pages\ViewSkillType::route('/{record}'),
            'edit'   => Pages\EditSkillType::route('/{record}/edit'),
        ];
    }
}
