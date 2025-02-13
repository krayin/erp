<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources;

use Webkul\Support\Filament\Resources\ActivityTypeResource as BaseActivityTypeResource;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource\Pages;
use Webkul\Sale\Filament\Clusters\Configuration;

class ActivityTypeResource extends BaseActivityTypeResource
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Activity Type');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Activities');
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
