<?php

namespace Webkul\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Support\Models\Currency;
use Carbon\Carbon;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\CompanyAddress;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::create([
            'sort'                => 1,
            'name'                => 'DummyCorp LLC',
            'tax_id'              => 'DUM123456',
            'registration_number' => 'DUMREG789',
            'company_id'          => 'DUMCOMP001',
            'email'               => 'dummy@dummycorp.local',
            'phone'               => '+0-000-000-0000',
            'mobile'              => '+0-111-111-1111',
            'color'               => '#AAAAAA',
            'is_active'           => true,
            'founded_date'        => '2000-01-01',
            'currency_id'         => Currency::inRandomOrder()->first()->id,
            'website'             => 'http://dummycorp.local',
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        ]);

        CompanyAddress::create([
            'company_id' => $company->id,
            'street1'    => '123 Placeholder Ave',
            'city'       => 'Faketown',
            'state_id'   => State::inRandomOrder()->first()->id,
            'country_id' => Country::inRandomOrder()->first()->id,
            'zip'        => '000000',
            'is_primary' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
