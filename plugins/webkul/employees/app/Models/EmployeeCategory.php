<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Employee\Database\Factories\EmployeeCategoryFactory;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;

class EmployeeCategory extends Model
{
    use HasCustomFields, HasFactory, SoftDeletes;

    protected $fillable = ['name', 'color', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): EmployeeCategoryFactory
    {
        return EmployeeCategoryFactory::new();
    }
}
