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
    public static string $name = 'saved-filters';

    public static string $viewNamespace = 'saved-filters';

    public function configureCustomPackage(Package $package): void
    {
        $package
            ->name('saved-filters')
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
            fn (): View => view('saved-filters::filament.resources.pages.list-records.favorites-bar'),
        );

        FilamentView::registerRenderHook(
            TablesRenderHook::TOOLBAR_SEARCH_AFTER,
            fn (): View => view('saved-filters::filament.tables.saved-filters'),
        );
    }
}
