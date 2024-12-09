<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSkill extends Model
{
    protected $fillable = [
        'employee_id',
        'skill_id',
        'skill_level_id',
        'start_date',
        'notes'
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
}
