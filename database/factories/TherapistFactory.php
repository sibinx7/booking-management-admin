<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Therapist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Therapist>
 */
class TherapistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory()->therapist(),
            'display_name' => null,
            'profile_pic' => 'images/therapists/default.jpg',
            'bio' => fake()->paragraph(),
            'education' => ['Certified Massage Therapist Level 2', 'Spa Therapy Advanced Course'],
            'is_online' => fake()->boolean(50),
            'commission_rate' => fake()->randomElement([40.00, 50.00, 60.00]),
            'rating' => fake()->randomFloat(2, 4.5, 5.0),
            'review_count' => fake()->numberBetween(5, 45),
        ];
    }
}
