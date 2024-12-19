<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Employee\Database\Factories\SkillFactory;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;

class Skill extends Model
{
    use HasCustomFields, HasFactory, SoftDeletes;

    protected $table = 'employees_skills';

    protected $fillable = [
        'sort',
        'name',
        'skill_type_id',
        'creator_id',
    ];

    public function skillType(): BelongsTo
    {
        return $this->belongsTo(SkillType::class, 'skill_type_id');
    }

    public function skillLevels()
    {
        return $this->hasMany(SkillLevel::class);
    }

    public function employeeSkills(): HasMany
    {
        return $this->hasMany(EmployeeSkill::class, 'skill_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): SkillFactory
    {
        return SkillFactory::new();
    }
}
