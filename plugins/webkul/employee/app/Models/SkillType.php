<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Employee\Models\Skill;
use Webkul\Employee\Models\SkillLevel;

class SkillType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function skillLevels(): HasMany
    {
        return $this->hasMany(SkillLevel::class, 'skill_type_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'skill_type_id');
    }
}
