<?php

namespace Webkul\Support\Filament\Clusters\Settings\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Spatie\Permission\Models\Role;
use Webkul\Support\Filament\Clusters\Settings;
use Webkul\Support\Settings\UserSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class ManageUsers extends SettingsPage
{
    use HasPageShield;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static string $settings = UserSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_user_invitation')
                    ->label('Enable User Invitation')
                    ->helperText('Allow admins to invite users by email with assigned roles and permissions.')
                    ->required(),
                Forms\Components\Toggle::make('enable_reset_password')
                    ->label('Enable Reset Password')
                    ->helperText('Allow users to reset their passwords from login page.')
                    ->required(),
                Forms\Components\Select::make('default_role_id')
                    ->label('Default Role')
                    ->helperText('Role assigned to users upon registration via invitation.')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }
}
