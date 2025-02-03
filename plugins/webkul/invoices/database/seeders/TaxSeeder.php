<?php

namespace Webkul\Invoice\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('invoices_taxes')->delete();

        $invoicesTaxes = [
            [
                'company_id' => 1,
                'sort' => 1,
                'tax_group_id' => 4,
                'cash_basis_transition_account_id' => null,
                'country_id' => 104,
                'creator_id' => 1,
                'type_tax_use' => 'none',
                'tax_scope' => null,
                'amount_type' => 'percent',
                'price_include_override' => null,
                'tax_exigibility' => 'on_invoice',
                'name' => '5% CESS S',
                'description' => '<p>CESS 5%</p>',
                'invoice_label' => 'CESS 5%',
                'invoice_legal_notes' => null,
                'amount' => 5.0000,
                'is_active' => true,
                'include_base_amount' => true,
                'is_base_affected' => false,
                'analytic' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'formula' => 'price_unit * 0.10',
            ],
            [
                'company_id' => 1,
                'sort' => 1,
                'tax_group_id' => 4,
                'cash_basis_transition_account_id' => null,
                'country_id' => 104,
                'creator_id' => 1,
                'type_tax_use' => 'none',
                'tax_scope' => null,
                'amount_type' => 'fixed',
                'price_include_override' => null,
                'tax_exigibility' => 'on_invoice',
                'name' => '1.591% CESS S',
                'description' => null,
                'invoice_label' => '1591 PER THOUSAND',
                'invoice_legal_notes' => null,
                'amount' => 1.5910,
                'is_active' => true,
                'include_base_amount' => true,
                'is_base_affected' => false,
                'analytic' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'formula' => 'price_unit * 0.10',
            ],
        ];

        DB::table('invoices_taxes')->insert($invoicesTaxes);
    }
}
