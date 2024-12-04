<?php

namespace Webkul\Security\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Spatie\Permission\Models\Role;
use Webkul\Security\Filament\Clusters\Settings;
use Webkul\Security\Settings\UserSettings;

class ManageUsers extends SettingsPage
{
    use HasPageShield;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static string $settings = UserSettings::class;

    public function getTitle(): string
    {
        return __('security::app.filament.clusters.pages.manage-users.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('security::app.filament.clusters.pages.manage-users.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_user_invitation')
                    ->label(__('security::app.filament.clusters.pages.manage-users.enable-user-invitation'))
                    ->helperText(__('security::app.filament.clusters.pages.manage-users.enable-user-invitation-helper-text'))
                    ->required(),
                Forms\Components\Toggle::make('enable_reset_password')
                    ->label(__('security::app.filament.clusters.pages.manage-users.enable-reset-password'))
                    ->helperText(__('security::app.filament.clusters.pages.manage-users.enable-reset-password-helper-text'))
                    ->required(),
                Forms\Components\Select::make('default_role_id')
                    ->label(__('security::app.filament.clusters.pages.manage-users.default-role'))
                    ->helperText(__('security::app.filament.clusters.pages.manage-users.default-role-helper-text'))
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }
}
