<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Employee\Database\Factories\DepartmentFactory;
use Webkul\Field\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class Department extends Model
{
    use HasChatter, HasCustomFields, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'employees_departments';

    protected $fillable = [
        'name',
        'manager_id',
        'company_id',
        'color',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobPositions(): HasMany
    {
        return $this->hasMany(EmployeeJobPosition::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): DepartmentFactory
    {
        return DepartmentFactory::new();
    }
}
