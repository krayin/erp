<?php

namespace Webkul\Inventory\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageTraceability extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Project';

    protected static string $settings = TraceabilitySettings::class;

    protected static ?string $cluster = Settings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('projects::filament/clusters/settings/pages/manage-tasks.title'),
        ];
    }

    public function getTitle(): string
    {
        return __('projects::filament/clusters/settings/pages/manage-tasks.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('projects::filament/clusters/settings/pages/manage-tasks.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_milestones')
                    ->label(__('projects::filament/clusters/settings/pages/manage-tasks.form.enable-milestones'))
                    ->helperText(__('projects::filament/clusters/settings/pages/manage-tasks.form.enable-milestones-helper-text'))
                    ->required(),
            ]);
    }
}
