<?php

namespace Webkul\Project\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Webkul\Project\Settings\TimeSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageTime extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Project';

    protected static string $settings = TimeSettings::class;

    protected static ?string $cluster = Settings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('projects::filament/clusters/settings/pages/manage-time.title'),
        ];
    }

    public function getTitle(): string
    {
        return __('projects::filament/clusters/settings/pages/manage-time.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('projects::filament/clusters/settings/pages/manage-time.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_timesheets')
                    ->label(__('projects::filament/clusters/settings/pages/manage-time.form.enable-timesheets'))
                    ->helperText(__('projects::filament/clusters/settings/pages/manage-time.form.enable-timesheets-helper-text'))
                    ->required(),
            ]);
    }
}
