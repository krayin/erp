<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Fields\Traits\HasCustomFields;

class SkillType extends Model
{
    use HasCustomFields, SoftDeletes;

    protected $fillable = [
        'name',
        'color',
        'status',
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
