<?php

namespace Webkul\Invoice\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaxGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('invoices_tax_groups')->delete();

        $now = Carbon::now();

        $taxGroups = [
            ['sort' => 1, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'SGST', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 2, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'CGST', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 3, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'IGST', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 4, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'CESS', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 5, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'GST', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 6, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'Exempt', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 7, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'Nil Rated', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 8, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'Non GST Supplies', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 9, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'TCS', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now],
            ['sort' => 10, 'company_id' => 1, 'country_id' => 104, 'creator_id' => 1, 'name' => 'TDS', 'preceding_subtotal' => null, 'created_at' => $now, 'updated_at' => $now]
        ];

        DB::table('invoices_tax_groups')->insert($taxGroups);
    }
}
