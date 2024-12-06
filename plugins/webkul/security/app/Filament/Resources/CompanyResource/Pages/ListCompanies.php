<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Webkul\Security\Filament\Resources\CompanyResource;
use Webkul\Security\Models\Company;
use Webkul\TableViews\Filament\Traits\HasTableViews;

class ListCompanies extends ListRecords
{
    use HasTableViews;

    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Companies')
                ->badge(Company::count()),
            'archived' => Tab::make('Archived')
                ->badge(Company::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
