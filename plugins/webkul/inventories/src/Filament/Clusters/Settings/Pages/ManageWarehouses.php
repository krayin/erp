<?php

namespace Webkul\Inventory\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageWarehouses extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 3;

    protected static string $settings = WarehouseSettings::class;

    protected static ?string $cluster = Settings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('inventories::filament/clusters/settings/pages/manage-warehouses.title'),
        ];
    }

    public function getTitle(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-warehouses.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-warehouses.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_locations')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-warehouses.form.enable-locations'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-warehouses.form.enable-locations-helper-text'))
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                        if (! $get('enable_locations')) {
                            $set('enable_multi_steps_routes', false);
                        }
                    })
                    ->live(),
                Forms\Components\Toggle::make('enable_multi_steps_routes')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-warehouses.form.enable-multi-steps-routes'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-warehouses.form.enable-multi-steps-routes-helper-text'))
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                        if ($get('enable_multi_steps_routes')) {
                            $set('enable_locations', true);
                        }
                    })
                    ->live(),
            ]);
    }
}
