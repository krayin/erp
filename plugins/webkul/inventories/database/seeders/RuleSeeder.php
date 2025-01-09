<?php

namespace Webkul\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Inventory\Enums;
use Webkul\Security\Models\User;

class RuleSeeder extends Seeder
{
    /**
     * Seed the application's database with currencies.
     */
    public function run(): void
    {
        $user = User::first();

        DB::table('inventories_rules')->delete();

        DB::table('inventories_rules')->insert([
            [
                'id'                       => 1,
                'sort'                     => 1,
                'name'                     => 'WH: Vendors → Stock',
                'route_sequence'           => 9,
                'delay'                    => 0,
                'group_propagation_option' => Enums\GroupPropagation::PROPAGATE,
                'action'                   => Enums\RuleAction::PULL,
                'procure_method'           => Enums\ProcureMethod::MAKE_TO_STOCK,
                'auto'                     => Enums\RuleAuto::MANUAL,
                'location_dest_from_rule'  => false,
                'propagate_cancel'         => false,
                'propagate_carrier'        => false,
                'source_location_id'       => 4,
                'destination_location_id'  => 12,
                'route_id'                 => 2,
                'picking_type_id'          => 1,
                'company_id'               => $user->default_company_id,
                'creator_id'               => $user->id,
                'created_at'               => now(),
                'updated_at'               => now(),
            ], [
                'id'                       => 2,
                'sort'                     => 2,
                'name'                     => 'WH: Stock → Customers',
                'route_sequence'           => 10,
                'delay'                    => 0,
                'group_propagation_option' => Enums\GroupPropagation::PROPAGATE,
                'action'                   => Enums\RuleAction::PULL,
                'procure_method'           => Enums\ProcureMethod::MAKE_TO_STOCK,
                'auto'                     => Enums\RuleAuto::MANUAL,
                'location_dest_from_rule'  => false,
                'propagate_cancel'         => false,
                'propagate_carrier'        => true,
                'source_location_id'       => 12,
                'destination_location_id'  => 5,
                'route_id'                 => 3,
                'picking_type_id'          => 1,
                'company_id'               => $user->default_company_id,
                'creator_id'               => $user->id,
                'created_at'               => now(),
                'updated_at'               => now(),
            ], [
                'id'                       => 3,
                'sort'                     => 3,
                'name'                     => 'WH: Vendors → Customers',
                'route_sequence'           => 20,
                'delay'                    => 0,
                'group_propagation_option' => Enums\GroupPropagation::PROPAGATE,
                'action'                   => Enums\RuleAction::PULL,
                'procure_method'           => Enums\ProcureMethod::MAKE_TO_STOCK,
                'auto'                     => Enums\RuleAuto::MANUAL,
                'location_dest_from_rule'  => false,
                'propagate_cancel'         => false,
                'propagate_carrier'        => false,
                'source_location_id'       => 4,
                'destination_location_id'  => 5,
                'route_id'                 => 4,
                'picking_type_id'          => 1,
                'company_id'               => $user->default_company_id,
                'creator_id'               => $user->id,
                'created_at'               => now(),
                'updated_at'               => now(),
            ], [
                'id'                       => 4,
                'sort'                     => 4,
                'name'                     => 'WH: Input → Output',
                'route_sequence'           => 20,
                'delay'                    => 0,
                'group_propagation_option' => Enums\GroupPropagation::PROPAGATE,
                'action'                   => Enums\RuleAction::PUSH,
                'procure_method'           => Enums\ProcureMethod::MAKE_TO_ORDER,
                'auto'                     => Enums\RuleAuto::MANUAL,
                'location_dest_from_rule'  => false,
                'propagate_cancel'         => false,
                'propagate_carrier'        => false,
                'source_location_id'       => 13,
                'destination_location_id'  => 15,
                'route_id'                 => 4,
                'picking_type_id'          => 1,
                'company_id'               => $user->default_company_id,
                'creator_id'               => $user->id,
                'created_at'               => now(),
                'updated_at'               => now(),
            ], [
                'id'                       => 5,
                'sort'                     => 5,
                'name'                     => 'WH: Stock → Customers (MTO)',
                'route_sequence'           => 5,
                'delay'                    => 0,
                'group_propagation_option' => Enums\GroupPropagation::PROPAGATE,
                'action'                   => Enums\RuleAction::PULL,
                'procure_method'           => Enums\ProcureMethod::MAKE_TO_ORDER,
                'auto'                     => Enums\RuleAuto::MANUAL,
                'location_dest_from_rule'  => false,
                'propagate_cancel'         => false,
                'propagate_carrier'        => true,
                'source_location_id'       => 12,
                'destination_location_id'  => 5,
                'route_id'                 => 1,
                'picking_type_id'          => 1,
                'company_id'               => $user->default_company_id,
                'creator_id'               => $user->id,
                'created_at'               => now(),
                'updated_at'               => now(),
            ],
        ]);
    }
}
