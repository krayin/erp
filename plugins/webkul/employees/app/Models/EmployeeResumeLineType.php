<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeResumeLineType extends Model
{
    protected $table = 'employees_employee_resume_line_types';

    protected $fillable = [
        'sort',
        'name',
        'creator_id',
    ];

    public function resume()
    {
        return $this->hasMany(EmployeeResume::class);
    }
}
