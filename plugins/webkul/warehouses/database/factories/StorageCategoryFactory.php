<?php

namespace Webkul\Warehouse\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Security\Models\User;
use Webkul\Warehouse\Models\StorageCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Webkul\Warehouse\Models\StorageCategory>
 */
class StorageCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StorageCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => fake()->name(),
            'sort'       => fake()->randomNumber(),
            'creator_id' => User::factory(),
        ];
    }
}
