<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Database\Factories\EmployeeSkillFactory;

class EmployeeSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'skill_id',
        'skill_level_id',
        'start_date',
        'notes',
    ];

    protected $dates = ['start_date'];

    // public function employee(): BelongsTo
    // {
    //     return $this->belongsTo(Employee::class, 'employee_id');
    // }

    // public function skill(): BelongsTo
    // {
    //     return $this->belongsTo(HrSkill::class, 'skill_id');
    // }

    // public function skillLevel(): BelongsTo
    // {
    //     return $this->belongsTo(HrSkillLevel::class, 'skill_level_id');
    // }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): EmployeeSkillFactory
    {
        return EmployeeSkillFactory::new();
    }
}
