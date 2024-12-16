<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Database\Factories\EmployeeAddressFactory;

class EmployeeAddress extends Model
{
    use HasFactory;

    protected $table = 'employees_addresses';

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): EmployeeAddressFactory
    {
        return EmployeeAddressFactory::new();
    }
}
