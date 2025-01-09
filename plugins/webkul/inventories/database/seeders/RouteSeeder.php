<?php

namespace Webkul\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;

class RouteSeeder extends Seeder
{
    /**
     * Seed the application's database with currencies.
     */
    public function run(): void
    {
        $user = User::first();

        DB::table('inventories_routes')->delete();

        DB::table('inventories_routes')->insert([
            [
                'id'                          => 1,
                'sort'                        => 1,
                'name'                        => 'Replenish on Order (MTO)',
                'product_selectable'          => true,
                'product_category_selectable' => false,
                'warehouse_selectable'        => false,
                'packaging_selectable'        => false,
                'company_id'                  => $user->default_company_id,
                'creator_id'                  => $user->id,
                'deleted_at'                  => now(),
                'created_at'                  => now(),
                'updated_at'                  => now(),
            ], [
                'id'                          => 2,
                'sort'                        => 2,
                'name'                        => 'Your Company: Receive in 1 step (Stock)',
                'product_selectable'          => false,
                'product_category_selectable' => true,
                'warehouse_selectable'        => true,
                'packaging_selectable'        => false,
                'company_id'                  => $user->default_company_id,
                'creator_id'                  => $user->id,
                'deleted_at'                  => null,
                'created_at'                  => now(),
                'updated_at'                  => now(),
            ], [
                'id'                          => 3,
                'sort'                        => 3,
                'name'                        => 'Your Company: Deliver in 1 step (Ship)',
                'product_selectable'          => false,
                'product_category_selectable' => true,
                'warehouse_selectable'        => true,
                'packaging_selectable'        => false,
                'company_id'                  => $user->default_company_id,
                'creator_id'                  => $user->id,
                'deleted_at'                  => null,
                'created_at'                  => now(),
                'updated_at'                  => now(),
            ], [
                'id'                          => 4,
                'sort'                        => 4,
                'name'                        => 'Your Company: Cross-Dock',
                'product_selectable'          => true,
                'product_category_selectable' => true,
                'warehouse_selectable'        => false,
                'packaging_selectable'        => false,
                'company_id'                  => $user->default_company_id,
                'creator_id'                  => $user->id,
                'deleted_at'                  => now(),
                'created_at'                  => now(),
                'updated_at'                  => now(),
            ],
        ]);
    }
}
