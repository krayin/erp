<?php

namespace Webkul\Invoice\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncotermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('invoices_incoterms')->delete();

        $incoterms = [
            ['code' => 'EXW', 'name' => 'EX WORKS', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'FCA', 'name' => 'FREE CARRIER', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'FAS', 'name' => 'FREE ALONGSIDE SHIP', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'FOB', 'name' => 'FREE ON BOARD', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CFR', 'name' => 'COST AND FREIGHT', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CIF', 'name' => 'COST, INSURANCE AND FREIGHT', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CPT', 'name' => 'CARRIAGE PAID TO', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CIP', 'name' => 'CARRIAGE AND INSURANCE PAID TO', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'DPU', 'name' => 'DELIVERED AT PLACE UNLOADED', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'DAP', 'name' => 'DELIVERED AT PLACE', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'DDP', 'name' => 'DELIVERED DUTY PAID', 'creator_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('invoices_incoterms')->insert($incoterms);
    }
}
