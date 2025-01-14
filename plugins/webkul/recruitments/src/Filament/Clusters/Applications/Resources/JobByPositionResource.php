<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources;

class JobByPositionResource extends ApplicantResource
{
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function getNavigationLabel(): string
    {
        return __('Job By Position');
    }

    public static function getSlug(): string
    {
        return 'recruitments';
    }
}
