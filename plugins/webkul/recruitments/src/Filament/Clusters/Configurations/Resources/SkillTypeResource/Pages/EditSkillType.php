<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages\EditSkillType as EditSkillTypeBase;

class EditSkillType extends EditSkillTypeBase
{
    protected static string $resource = SkillTypeResource::class;
}
