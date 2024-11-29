<?php

namespace Webkul\Security\Filament\Resources\TeamResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Webkul\Security\Filament\Resources\TeamResource;

class ManageTeams extends ManageRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus-circle'),
        ];
    }
}
