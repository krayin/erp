<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webkul\Partner\Models\Address;

class CompanyAddress extends Model
{
    protected $fillable = [
        'street1',
        'street2',
        'city',
        'zip',
        'is_primary',
        'state_id',
        'country_id',
        'company_id',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($companyAddress) {
            DB::transaction(function () use ($companyAddress) {
                $partnerAddress = Address::create([
                    'name'       => $companyAddress->company->name,
                    'type'       => 'permanent',
                    'email'      => $companyAddress->company->email ?? null,
                    'phone'      => $companyAddress->company->phone ?? null,
                    'street1'    => $companyAddress->street1,
                    'street2'    => $companyAddress->street2,
                    'city'       => $companyAddress->city,
                    'zip'        => $companyAddress->zip,
                    'state_id'   => $companyAddress->state_id,
                    'country_id' => $companyAddress->country_id,
                    'creator_id' => $companyAddress->company->creator_id,
                    'partner_id' => $companyAddress->company->partner_id ?? 1,
                ]);

                $companyAddress->partner_address_id = $partnerAddress->id;
            });
        });

        static::updating(function ($companyAddress) {
            if ($companyAddress->partnerAddress) {
                $companyAddress->partnerAddress->update([
                    'name'       => $companyAddress->company->name,
                    'type'       => 'permanent',
                    'email'      => $companyAddress->company->email ?? null,
                    'phone'      => $companyAddress->company->phone ?? null,
                    'street1'    => $companyAddress->street1,
                    'street2'    => $companyAddress->street2,
                    'city'       => $companyAddress->city,
                    'zip'        => $companyAddress->zip,
                    'state_id'   => $companyAddress->state_id,
                    'country_id' => $companyAddress->country_id,
                    'creator_id' => $companyAddress->company->creator_id,
                    'partner_id' => $companyAddress->company->partner_id ?? 1,
                ]);
            }
        });
    }
}
