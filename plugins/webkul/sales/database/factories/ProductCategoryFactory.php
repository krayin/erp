<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Webkul\Sale\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id'                                 => null,
            'creator_id'                                => null,
            'name'                                      => $this->faker->name,
            'complete_name'                             => $this->faker->name,
            'parent_path'                               => $this->faker->name,
            'product_properties_definition'             => $this->faker->name,
            'property_account_income_category_id'       => null,
            'property_account_expense_category_id'      => null,
            'property_account_down_payment_category_id' => null,
        ];
    }
}
