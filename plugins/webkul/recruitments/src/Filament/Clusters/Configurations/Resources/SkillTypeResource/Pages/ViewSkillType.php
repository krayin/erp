<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages\ViewSkillType as ViewSkillTypeBase;

class ViewSkillType extends ViewSkillTypeBase
{
    protected static string $resource = SkillTypeResource::class;
}
