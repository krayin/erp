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

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 4;

    protected static string $settings = TraceabilitySettings::class;

    protected static ?string $cluster = Settings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('inventories::filament/clusters/settings/pages/manage-traceability.title'),
        ];
    }

    public function getTitle(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-traceability.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-traceability.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_lots_serial_numbers')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-traceability.form.enable-lots-serial-numbers'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-traceability.form.enable-lots-serial-numbers-helper-text'))
                    ->live(),
                Forms\Components\Toggle::make('enable_expiration_dates')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-traceability.form.enable-expiration-dates'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-traceability.form.enable-expiration-dates-helper-text'))
                    ->visible(fn (Forms\Get $get) => $get('enable_lots_serial_numbers')),
                Forms\Components\Toggle::make('display_on_delivery_slips')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-traceability.form.display-on-delivery-slips'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-traceability.form.display-on-delivery-slips-helper-text'))
                    ->visible(fn (Forms\Get $get) => $get('enable_lots_serial_numbers'))
                    ->live(),
                Forms\Components\Toggle::make('display_expiration_dates_on_delivery_slips')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-traceability.form.display-expiration-dates-on-delivery-slips'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-traceability.form.display-expiration-dates-on-delivery-slips-helper-text'))
                    ->visible(fn (Forms\Get $get) => $get('enable_lots_serial_numbers') && $get('display_on_delivery_slips')),
                Forms\Components\Toggle::make('enable_consignments')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-traceability.form.enable-consignments'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-traceability.form.enable-consignments-helper-text')),
            ]);
    }
}
