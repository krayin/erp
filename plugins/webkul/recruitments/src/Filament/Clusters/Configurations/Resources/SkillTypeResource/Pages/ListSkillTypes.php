<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages\ListSkillTypes as ListSkillTypesBase;

class ListSkillTypes extends ListSkillTypesBase
{
    protected static string $resource = SkillTypeResource::class;
}
