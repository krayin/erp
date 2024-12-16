<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEmployeeResumeLineType extends Model
{
    protected $table = 'employee_employee_resume_line_types';

    protected $fillable = [
        'sort',
        'name',
        'creator_id',
    ];

    public function resume()
    {
        return $this->hasMany(EmployeeEmployeeResume::class);
    }
}
