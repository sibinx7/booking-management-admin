<?php

namespace Database\Factories;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpenseCategory>
 */
class ExpenseCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Electricity & Power', 'Commercial Water Supply', 'AC & Equipment Service', 'Laundry & Linen Care', 'Premises Rent']);

        return [
            'name' => $name,
            'code' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
