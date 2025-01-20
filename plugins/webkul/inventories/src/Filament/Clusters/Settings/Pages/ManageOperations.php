<?php

namespace Webkul\Inventory\Filament\Clusters\Settings\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageOperations extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 1;

    protected static string $settings = OperationSettings::class;

    protected static ?string $cluster = Settings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('inventories::filament/clusters/settings/pages/manage-operations.title'),
        ];
    }

    public function getTitle(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-operations.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-operations.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_packages')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-operations.form.enable-packages'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-operations.form.enable-packages-helper-text')),
                Forms\Components\Toggle::make('enable_warnings')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-operations.form.enable-warnings'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-operations.form.enable-warnings-helper-text')),
                Forms\Components\Toggle::make('enable_reception_report')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-operations.form.enable-reception-report'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-operations.form.enable-reception-report-helper-text')),
                Forms\Components\TextInput::make('annual_inventory_day')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-operations.form.annual-inventory-day'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-operations.form.annual-inventory-day-helper-text'))
                    ->integer()
                    ->minValue(1)
                    ->maxValue(31)
                    ->required(),
                Forms\Components\Select::make('annual_inventory_month')
                    ->label(__('inventories::filament/clusters/settings/pages/manage-operations.form.annual-inventory-month'))
                    ->options([
                        1 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.january'),
                        2 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.february'),
                        3 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.march'),
                        4 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.april'),
                        5 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.may'),
                        6 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.june'),
                        7 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.july'),
                        8 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.august'),
                        9 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.september'),
                        10 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.october'),
                        11 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.november'),
                        12 => __('inventories::filament/clusters/settings/pages/manage-operations.form.months.december'),
                    ])
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-operations.form.annual-inventory-month-helper-text'))
                    ->required(),
            ]);
    }
}
