<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\TeamResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Sale\Models\Team;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['sort'] = Team::max('sort') + 1;

        return $data;
    }
}
