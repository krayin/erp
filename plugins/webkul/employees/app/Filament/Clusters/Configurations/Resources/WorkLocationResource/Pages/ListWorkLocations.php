<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\WorkLocationResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\WorkLocationResource;

class ListWorkLocations extends ListRecords
{
    protected static string $resource = WorkLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = Auth::user()->id;

                    return $data;
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            null     => Tab::make('All'),
            'office' => Tab::make()
                ->icon('heroicon-m-building-office-2')
                ->query(fn ($query) => $query->where('location_type', 'office')),
            'home'   => Tab::make()
                ->icon('heroicon-m-home')->query(fn ($query) => $query->where('location_type', 'home')),
            'other'  => Tab::make()
                ->icon('heroicon-m-map-pin')->query(fn ($query) => $query->where('location_type', 'other')),
        ];
    }
}
