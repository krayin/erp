<?php

namespace Webkul\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Inventory\Enums\DeliveryStep;
use Webkul\Inventory\Enums\ReceptionStep;

class WarehouseSeeder extends Seeder
{
    /**
     * Seed the application's database with currencies.
     */
    public function run(): void
    {
        DB::table('inventories_warehouses')->delete();

        DB::table('inventories_warehouses')->insert([
            [
                'id'                       => 1,
                'name'                     => 'Your Company',
                'code'                     => 'WH',
                'sort'                     => 1,
                'reception_steps'          => ReceptionStep::ONE_STEP,
                'delivery_steps'           => DeliveryStep::ONE_STEP,
                'partner_address_id'       => 1,
                'company_id'               => 1,
                'creator_id'               => 1,
                'created_at'               => now(),
                'updated_at'               => now(),
                'view_location_id'         => 11,
                'lot_stock_location_id'    => 12,
                'input_stock_location_id'  => 13,
                'qc_stock_location_id'     => 14,
                'output_stock_location_id' => 15,
                'pack_stock_location_id'   => 16,
                // 'mto_pull_id'              => 1,
                // 'pick_type_id'             => 1,
                // 'pack_type_id'             => 1,
                // 'out_type_id'              => 1,
                // 'in_type_id'               => 1,
                // 'internal_type_id'         => 1,
                // 'qc_type_id'               => 1,
                // 'store_type_id'            => 1,
                // 'xdock_type_id'            => 1,
                // 'crossdock_route_id'       => 1,
                // 'reception_route_id'       => 1,
                // 'delivery_route_id'        => 1,
            ],
        ]);

        DB::table('inventories_locations')->whereIn('id', [11, 12, 13, 14, 15, 16])->update([
            'warehouse_id' => 1,
        ]);
    }
}
