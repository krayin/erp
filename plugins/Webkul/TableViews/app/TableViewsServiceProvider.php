<?php

namespace Webkul\TableViews;

use Illuminate\Support\Facades\Gate;
use Webkul\Core\Package;
use Webkul\Core\PackageServiceProvider;
use Webkul\TableViews\Models\TableView;
use Webkul\TableViews\Policies\TableViewPolicy;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Tables\View\TablesRenderHook;
use Illuminate\Contracts\View\View;

class TableViewsServiceProvider extends PackageServiceProvider
{
    public function configureCustomPackage(Package $package): void
    {
        $package
            ->name('table-views')
            ->hasViews()
            ->hasMigrations([
                '2024_11_19_142134_create_table_views_table',
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
