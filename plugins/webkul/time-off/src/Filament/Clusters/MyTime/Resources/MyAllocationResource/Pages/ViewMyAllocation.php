<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource\Pages;

use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMyAllocation extends ViewRecord
{
    protected static string $resource = MyAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
