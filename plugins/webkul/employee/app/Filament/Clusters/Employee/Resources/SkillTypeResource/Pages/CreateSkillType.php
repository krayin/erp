<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource\Pages;

use Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateSkillType extends CreateRecord
{
    protected static string $resource = SkillTypeResource::class;
}
