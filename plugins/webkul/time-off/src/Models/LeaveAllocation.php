<?php

namespace Webkul\TimeOff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Models\Department;
use Webkul\Employee\Models\Employee;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class LeaveAllocation extends Model
{
    use HasFactory;

    protected $table = 'time_off_leave_allocations';

    protected $fillable = [
        'holiday_status_id',
        'employee_id',
        'employee_company_id',
        'manager_id',
        'approver_id',
        'second_approver_id',
        'department_id',
        'accrual_plan_id',
        'creator_id',
        'name',
        'state',
        'allocation_type',
        'date_from',
        'date_to',
        'last_executed_carryover_date',
        'last_called',
        'actual_last_called',
        'next_call',
        'carried_over_days_expiration_date',
        'notes',
        'already_accrued',
        'number_of_days',
        'number_of_hours_display',
        'yearly_accrued_amount',
        'expiring_carryover_days',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'employee_company_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approver_id');
    }

    public function secondApprover()
    {
        return $this->belongsTo(Employee::class, 'second_approver_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function accrualPlan()
    {
        return $this->belongsTo(LeaveAccrualPlan::class, 'accrual_plan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function holidayStatus()
    {
        return $this->belongsTo(LeaveType::class, 'holiday_status_id');
    }
}
