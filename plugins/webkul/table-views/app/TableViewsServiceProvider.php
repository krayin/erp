<?php

namespace Webkul\TableViews;

use Filament\Support\Facades\FilamentView;
use Filament\Tables\View\TablesRenderHook;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;
use Webkul\TableViews\Models\TableView;
use Webkul\TableViews\Policies\TableViewPolicy;

class TableViewsServiceProvider extends PackageServiceProvider
{
    public function configureCustomPackage(Package $package): void
    {
        $package
            ->name('table-views')
            ->hasViews()
            ->hasMigrations([
                '2024_11_19_142134_create_table_views_table',
                '2024_11_21_142134_create_table_view_favorites_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Gate::policy(TableView::class, TableViewPolicy::class);
    }

    public function packageRegistered()
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE,
            fn (): View => view('table-views::filament.resources.pages.list-records.favorites-views'),
        );

        FilamentView::registerRenderHook(
            TablesRenderHook::TOOLBAR_SEARCH_AFTER,
            fn (): View => view('table-views::filament.tables.table-views'),
        );
    }
}
