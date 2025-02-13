<?php

namespace Webkul\Account\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts_payment_methods')->delete();

        $now = now();

        $paymentMethods = [
            [
                'id'           => 1,
                'code'         => 'manual',
                'payment_type' => 'inbound',
                'name'         => 'Manual Payment',
                'created_by'   => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'id'           => 2,
                'code'         => 'manual',
                'payment_type' => 'outbound',
                'name'         => 'Manual Payment',
                'created_by'   => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        DB::table('accounts_payment_methods')->insert($paymentMethods);
    }
}
