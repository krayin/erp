<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource;

class EditSkillType extends EditRecord
{
    protected static string $resource = SkillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
