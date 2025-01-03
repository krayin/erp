<?php

namespace Webkul\Security\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Webkul\Security\Settings\UserSettings;
use Webkul\Support\Filament\Clusters\Settings;
use Webkul\Support\Filament\Resources\EmailTemplateResource;

class ManageEmailTemplates extends SettingsPage
{
    use HasPageShield;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = UserSettings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('Manage Email Templates'),
        ];
    }

    public function getTitle(): string
    {
        return __('Manage Email Templates');
    }

    public static function getNavigationLabel(): string
    {
        return __('Manage Email Templates');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('email_template_description')
                    ->label(__('Email Templates'))
                    ->content(__('Configure the email templates that are sent to users.')),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('manageActivityTypes')
                        ->label(__('Email Templates'))
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->link()
                        ->url(EmailTemplateResource::getUrl('index')),
                ]),
            ])->columns(1);
    }

    public function getFormActions(): array
    {
        return [];
    }
}
