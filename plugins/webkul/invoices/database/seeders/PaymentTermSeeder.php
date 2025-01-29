<?php

namespace Webkul\Invoice\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Invoice\Enums\EarlyPayDiscount;

class PaymentTermSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('invoices_payment_terms')->delete();

        $paymentTerms = [
            [
                'id'                => 1,
                'company_id'        => 1,
                'sort'              => 0,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '21 Days',
                'note'              => '<p>Payment terms: 21 Days</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => null,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 2,
                'company_id'        => 1,
                'sort'              => 1,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '30 Days',
                'note'              => '<p>Payment terms: 30 Days</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => null,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 3,
                'company_id'        => 1,
                'sort'              => 2,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '45 Days',
                'note'              => '<p>Payment terms: 45 Days</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => null,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 4,
                'company_id'        => 1,
                'sort'              => 3,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => 'End of Following Month',
                'note'              => '<p>Payment terms: End of Following Month</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => null,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 5,
                'company_id'        => 1,
                'sort'              => 4,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '10 Days after End of Next Month',
                'note'              => '<p>Payment terms: 10 Days after End of Next Month</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => null,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 6,
                'company_id'        => 1,
                'sort'              => 5,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '30% Now, Balance 60 Days',
                'note'              => '<p>Payment terms: 30% Now, Balance 60 Days</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => null,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 7,
                'company_id'        => 1,
                'sort'              => 6,
                'discount_days'     => 7,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '2/7 Net 30',
                'note'              => '<p>Payment terms: 30 Days, 2% Early Payment Discount under 7 days</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => true,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 8,
                'company_id'        => 1,
                'sort'              => 7,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '90 Days, on the 10th',
                'note'              => '<p>Payment terms: 90 days, on the 10th</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => null,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 9,
                'company_id'        => 1,
                'sort'              => 8,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '30% Advance End of Following Month',
                'note'              => '<p>Payment terms: 30% Advance End of Following Month</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => null,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 10,
                'company_id'        => 1,
                'sort'              => 9,
                'discount_days'     => 5,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => 'Immediate Payment',
                'note'              => '<p>Payment terms: Immediate Payment</p>',
                'is_active'         => true,
                'display_on_invoice' => true,
                'early_discount'    => true,
                'discount_percentage' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'                => 11,
                'company_id'        => 1,
                'sort'              => 10,
                'discount_days'     => 10,
                'creator_id'        => 1,
                'early_pay_discount' => EarlyPayDiscount::INCLUDED->value,
                'name'              => '15 Days',
                'note'              => '<p>Payment terms: 15 Days</p>',
                'is_active'         => true,
                'display_on_invoice' => false,
                'early_discount'    => false,
                'discount_percentage' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('invoices_payment_terms')->insert($paymentTerms);
    }
}
