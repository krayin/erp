<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Employee\Database\Factories\SkillFactory;
use Webkul\Fields\Traits\HasCustomFields;

class Skill extends Model
{
    use HasCustomFields, HasFactory, SoftDeletes;

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

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): SkillFactory
    {
        return SkillFactory::new();
    }
}
