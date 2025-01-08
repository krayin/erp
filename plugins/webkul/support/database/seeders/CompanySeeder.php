<?php

namespace Webkul\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;
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
            'name'                => 'Webkul Software',
            'tax_id'              => 'TAX123456789',
            'registration_number' => 'REG123456',
            'company_id'          => 'COMP123456',
            'email'               => 'contact@webkul.com',
            'phone'               => '+1-555-123-4567',
            'mobile'              => '+1-555-987-6543',
            'color'               => '#FF5733',
            'is_active'           => true,
            'founded_date'        => '1990-01-01',
            'currency_id'         => Currency::where('name', 'USD')->first()->id,
            'website'             => 'https://www.webkul.com',
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        ]);

        CompanyAddress::create([
            'company_id' => $company->id,
            'street1'    => 'ARV Park, H-28 Sector 63',
            'city'       => 'Noida',
            'state_id'   => State::where('code', 'UP')->first()->id,
            'country_id' => Country::where('code', 'IN')->first()->id,
            'zip'        => '201301',
            'is_primary' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
