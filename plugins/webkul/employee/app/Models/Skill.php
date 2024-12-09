<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'skill_type_id',
    ];

    public function skillType(): BelongsTo
    {
        return $this->belongsTo(SkillType::class, 'skill_type_id');
    }

    public function employeeSkills(): HasMany
    {
        return $this->hasMany(EmployeeSkill::class, 'skill_id');
    }
}
