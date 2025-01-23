<?php

namespace Webkul\Inventory\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Webkul\Inventory\Settings\ProductSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageProducts extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 2;

    protected static string $settings = ProductSettings::class;

    protected static ?string $cluster = Settings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('inventories::filament/clusters/settings/pages/manage-products.title'),
        ];
    }

    public function getTitle(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-products.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-products.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_variants')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-products.form.enable-variants'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-products.form.enable-variants-helper-text')),
                Forms\Components\Toggle::make('enable_uom')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-products.form.enable-uom'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-products.form.enable-uom-helper-text')),
                Forms\Components\Toggle::make('enable_packagings')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-products.form.enable-packagings'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-products.form.enable-packagings-helper-text')),
            ]);
    }
}
