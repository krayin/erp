<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class JobPositionSkill extends Model
{
    protected $table = 'job_position_skills';

    protected $fillable = ['job_position_id', 'skill_id'];

    public $timestamps = false;

    public function jobPosition()
    {
        return $this->belongsTo(EmployeeJobPosition::class, 'job_position_id');
    }

    public function skill()
    {
        return $this->belongsTo(EmployeeSkill::class, 'skill_id');
    }
}
