<?php

namespace Database\Factories;

use App\Models\TherapistAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TherapistAvailability>
 */
class TherapistAvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'therapist_id' => null,
            'day_of_week' => fake()->numberBetween(0, 6),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'is_available' => true,
        ];
    }
}
