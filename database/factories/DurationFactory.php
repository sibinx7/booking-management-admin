<?php

namespace Database\Factories;

use App\Models\Duration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Duration>
 */
class DurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $minutes = fake()->randomElement([30, 60, 90, 120, 150]);
        return [
            'minutes' => $minutes,
            'display_text' => "{$minutes} mins",
        ];
    }
}
