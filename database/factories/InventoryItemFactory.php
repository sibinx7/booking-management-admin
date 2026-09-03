<?php

namespace Database\Factories;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => InventoryCategory::factory(),
            'name' => fake()->words(3, true),
            'sku' => 'SKU-' . fake()->unique()->numerify('#####'),
            'unit' => fake()->randomElement(['bottles', 'liters', 'pieces', 'boxes']),
            'current_stock' => fake()->numberBetween(10, 100),
            'reorder_threshold' => 10.00,
            'unit_cost' => fake()->randomFloat(2, 150, 1200),
            'supplier_name' => fake()->company(),
            'notes' => 'Stock in active inventory',
            'is_active' => true,
        ];
    }
}
