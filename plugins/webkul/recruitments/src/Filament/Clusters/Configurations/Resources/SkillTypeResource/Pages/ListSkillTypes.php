<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;

use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages\ListSkillTypes as ListSkillTypesBase;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource;

class ListSkillTypes extends ListSkillTypesBase
{
    protected static string $resource = SkillTypeResource::class;
}
