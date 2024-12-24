<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Pages;

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
            __('security::app.filament.clusters.settings.name'),
        ];
    }

    public function getTitle(): string
    {
        return 'Manage Time';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Time';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_timesheets')
                    ->label('Enable Timesheets')
                    ->helperText('Track time spent on projects and tasks')
                    ->required(),
            ]);
    }
}
