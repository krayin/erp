<?php

namespace Webkul\Sale\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sales_product_categories')->delete();

        $categories = [
            [
                'id'                                        => 1,
                'parent_id'                                 => null,
                'creator_id'                                => 1,
                'name'                                      => 'All',
                'complete_name'                             => 'All',
                'parent_path'                               => '/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 10:49:51',
                'updated_at'                                => '2025-01-28 10:49:51',
            ],
            [
                'id'                                        => 2,
                'parent_id'                                 => 1,
                'creator_id'                                => 1,
                'name'                                      => 'Consumable',
                'complete_name'                             => 'All / Consumable',
                'parent_path'                               => '/1/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 10:50:15',
                'updated_at'                                => '2025-01-28 10:50:15',
            ],
            [
                'id'                                        => 3,
                'parent_id'                                 => 1,
                'creator_id'                                => 1,
                'name'                                      => 'Expenses',
                'complete_name'                             => 'All / Expenses',
                'parent_path'                               => '/1/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 10:55:42',
                'updated_at'                                => '2025-01-28 10:55:42',
            ],
            [
                'id'                                        => 4,
                'parent_id'                                 => 1,
                'creator_id'                                => 1,
                'name'                                      => 'Home Construction',
                'complete_name'                             => 'All / Home Construction',
                'parent_path'                               => '/1/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 10:55:56',
                'updated_at'                                => '2025-01-28 10:56:43',
            ],
            [
                'id'                                        => 5,
                'parent_id'                                 => 1,
                'creator_id'                                => 1,
                'name'                                      => 'Internal',
                'complete_name'                             => 'All / Internal',
                'parent_path'                               => '/1/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 10:56:07',
                'updated_at'                                => '2025-01-28 10:56:27',
            ],
            [
                'id'                                        => 6,
                'parent_id'                                 => 1,
                'creator_id'                                => 1,
                'name'                                      => 'Saleable',
                'complete_name'                             => 'All / Saleable',
                'parent_path'                               => '/1/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 10:56:55',
                'updated_at'                                => '2025-01-28 10:56:55',
            ],
            [
                'id'                                        => 7,
                'parent_id'                                 => 6,
                'creator_id'                                => 1,
                'name'                                      => 'Office Furniture',
                'complete_name'                             => 'All / Saleable / Office Furniture',
                'parent_path'                               => '/1/6/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 10:57:14',
                'updated_at'                                => '2025-01-28 11:04:41',
            ],
            [
                'id'                                        => 8,
                'parent_id'                                 => 6,
                'creator_id'                                => 1,
                'name'                                      => 'Outdoor furniture',
                'complete_name'                             => 'All / Saleable / Outdoor furniture',
                'parent_path'                               => '/1/6/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 11:05:41',
                'updated_at'                                => '2025-01-28 11:05:41',
            ],
            [
                'id'                                        => 9,
                'parent_id'                                 => 6,
                'creator_id'                                => 1,
                'name'                                      => 'Services',
                'complete_name'                             => 'All / Saleable / Services',
                'parent_path'                               => '/1/6/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 11:06:17',
                'updated_at'                                => '2025-01-28 11:06:17',
            ],
            [
                'id'                                        => 10,
                'parent_id'                                 => 9,
                'creator_id'                                => 1,
                'name'                                      => 'Saleable',
                'complete_name'                             => 'All / Saleable / Services / Saleable',
                'parent_path'                               => '/1/6/9/',
                'product_properties_definition'             => null,
                'property_account_income_category_id'       => null,
                'property_account_expense_category_id'      => null,
                'property_account_down_payment_category_id' => null,
                'created_at'                                => '2025-01-28 11:07:38',
                'updated_at'                                => '2025-01-28 11:07:38',
            ],
        ];

        DB::table('sales_product_categories')->insert($categories);
    }
}
