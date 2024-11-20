<?php

namespace Webkul\SavedFilters;

use Illuminate\Support\Facades\Gate;
use Webkul\Core\Package;
use Webkul\Core\PackageServiceProvider;
use Webkul\SavedFilters\Models\SavedFilter;
use Webkul\SavedFilters\Policies\SavedFilterPolicy;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Tables\View\TablesRenderHook;
use Illuminate\Contracts\View\View;

class SavedFiltersServiceProvider extends PackageServiceProvider
{
    public static string $name = 'saved_filters';

    public static string $viewNamespace = 'saved_filters';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasMigrations([
                '2024_11_19_142134_create_saved_filters_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Gate::policy(SavedFilter::class, SavedFilterPolicy::class);
    }

    public function packageRegistered()
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE,
            fn (): View => view('saved_filters::filament.table.favorites'),
        );

        FilamentView::registerRenderHook(
            TablesRenderHook::TOOLBAR_SEARCH_AFTER,
            fn (): View => view('saved_filters::filament.table.saved-filters'),
        );
    }
}
