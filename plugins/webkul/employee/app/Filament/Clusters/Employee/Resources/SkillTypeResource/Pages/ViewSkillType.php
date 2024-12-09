<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource\Pages;

use Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSkillType extends ViewRecord
{
    protected static string $resource = SkillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
