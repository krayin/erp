<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListProductTags extends ListRecords
{
    protected static string $resource = ProductTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data) {
                    $data['creator_id'] = Auth::id();

                    return $data;
                }),
        ];
    }
}
