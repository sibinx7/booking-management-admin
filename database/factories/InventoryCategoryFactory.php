<?php

namespace Database\Factories;

use App\Models\InventoryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryCategory>
 */
class InventoryCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Massage Oils & Aromas', 'Linens & Towels', 'Bathroom & Toiletries', 'Therapy Stones & Basalt', 'Herbal Extracts & Scrubs']);

        return [
            'name' => $name,
            'code' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
