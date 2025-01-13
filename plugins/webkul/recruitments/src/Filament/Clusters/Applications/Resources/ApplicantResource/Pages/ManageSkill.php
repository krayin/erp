<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Filament\Resources\Pages\ManageRelatedRecords;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Webkul\Recruitment\Traits\CandidateSkillRelation;

class ManageSkill extends ManageRelatedRecords
{
    use CandidateSkillRelation;

    protected static string $resource = ApplicantResource::class;

    protected static string $relationship = 'skills';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function getNavigationLabel(): string
    {
        return 'Manage Skills';
    }
}
