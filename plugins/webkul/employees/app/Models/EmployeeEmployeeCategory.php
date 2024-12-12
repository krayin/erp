<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEmployeeCategory extends Model
{
    protected $table = 'employee_employee_categories';

    protected $fillable = ['employee_id', 'category_id'];

    public $timestamps = false;
}
