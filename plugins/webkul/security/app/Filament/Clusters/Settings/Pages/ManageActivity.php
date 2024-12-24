<?php

namespace Webkul\Security\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Webkul\Support\Filament\Clusters\Settings;
use Webkul\Security\Settings\UserSettings;

class ManageActivity extends SettingsPage
{
    use HasPageShield;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = UserSettings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('security::app.filament.clusters.settings.name'),
        ];
    }

    public function getTitle(): string
    {
        return 'Manage Activities';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Activities';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('activity_description')
                    ->label('Activities')
                    ->content('Configure your activity types.'),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('manageActivityTypes')
                        ->label('Activity Types')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->link()
                        ->url(route('filament.admin.resources.settings.activity-types.index')),
                ]),
            ])->columns(1);
    }

    public function getFormActions(): array
    {
        return [];
    }
}
