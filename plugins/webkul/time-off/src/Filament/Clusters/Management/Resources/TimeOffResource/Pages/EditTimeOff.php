<?php

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\Pages;

use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeOff extends EditRecord
{
    protected static string $resource = TimeOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
