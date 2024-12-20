<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Webkul\Employee\Database\Factories\EmployeeAddressFactory;
use Webkul\Partner\Models\Address;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;

class EmployeeAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees_addresses';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'state_id',
        'country_id',
        'creator_id',
        'partner_address_id',
        'is_primary',
        'street1',
        'street2',
        'city',
        'zip',
        'type',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function newFactory(): EmployeeAddressFactory
    {
        return EmployeeAddressFactory::new();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function partnerAddress()
    {
        return $this->belongsTo(Address::class, 'partner_address_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($employeeAddress) {
            DB::transaction(function () use ($employeeAddress) {
                $partnerAddress = Address::create([
                    'name'       => $employeeAddress->employee->name,
                    'type'       => $employeeAddress->type,
                    'email'      => $employeeAddress->employee->work_email ?? null,
                    'phone'      => $employeeAddress->employee->mobile_phone ?? null,
                    'street1'    => $employeeAddress->street1,
                    'street2'    => $employeeAddress->street2,
                    'city'       => $employeeAddress->city,
                    'zip'        => $employeeAddress->zip,
                    'state_id'   => $employeeAddress->state_id,
                    'country_id' => $employeeAddress->country_id,
                    'creator_id' => $employeeAddress->creator_id,
                    'partner_id' => $employeeAddress->employee->partner_id,
                ]);

                $employeeAddress->partner_address_id = $partnerAddress->id;
            });
        });

        static::updating(function ($employeeAddress) {
            if ($employeeAddress->partnerAddress) {
                $employeeAddress->partnerAddress->update([
                    'name'       => $employeeAddress->employee->name,
                    'type'       => $employeeAddress->type,
                    'email'      => $employeeAddress->employee->work_email ?? null,
                    'phone'      => $employeeAddress->employee->mobile_phone ?? null,
                    'street1'    => $employeeAddress->street1,
                    'street2'    => $employeeAddress->street2,
                    'city'       => $employeeAddress->city,
                    'zip'        => $employeeAddress->zip,
                    'state_id'   => $employeeAddress->state_id,
                    'country_id' => $employeeAddress->country_id,
                ]);
            }
        });
    }
}
