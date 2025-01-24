<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources;

use Webkul\Support\Filament\Resources\ActivityTypeResource as BaseActivityTypeResource;
use Webkul\TimeOff\Filament\Clusters\Configurations;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

class ActivityTypeResource extends BaseActivityTypeResource
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $cluster = Configurations::class;

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('Activity Type');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListActivityTypes::route('/'),
            'create' => Pages\CreateActivityType::route('/create'),
            'edit'   => Pages\EditActivityType::route('/{record}/edit'),
            'view'   => Pages\ViewActivityType::route('/{record}'),
        ];
    }
}
