<?php

namespace Webkul\Inventory\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Webkul\Inventory\Settings\LogisticSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageLogistics extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 5;

    protected static string $settings = LogisticSettings::class;

    protected static ?string $cluster = Settings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('inventories::filament/clusters/settings/pages/manage-logistics.title'),
        ];
    }

    public function getTitle(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-logistics.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-logistics.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_dropshipping')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-logistics.form.enable-dropshipping'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-logistics.form.enable-dropshipping-helper-text')),
            ]);
    }
}
