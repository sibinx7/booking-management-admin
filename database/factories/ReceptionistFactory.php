<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Receptionist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Receptionist>
 */
class ReceptionistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory()->receptionist(),
            'counter_number' => 'Counter ' . fake()->numberBetween(1, 3),
            'shift_preference' => fake()->randomElement(['morning', 'evening', 'general']),
            'desk_phone' => '+91 80 2345 ' . fake()->numerify('####'),
            'is_active' => true,
        ];
    }
}
