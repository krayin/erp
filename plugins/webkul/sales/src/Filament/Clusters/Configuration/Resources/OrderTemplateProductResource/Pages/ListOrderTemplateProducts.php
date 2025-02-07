<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\OrderTemplateProductResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\OrderTemplateProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderTemplateProducts extends ListRecords
{
    protected static string $resource = OrderTemplateProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
