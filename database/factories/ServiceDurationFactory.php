<?php

namespace Database\Factories;

use App\Models\Duration;
use App\Models\Service;
use App\Models\ServiceDuration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceDuration>
 */
class ServiceDurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'duration_id' => Duration::factory(),
            'price' => fake()->randomFloat(2, 50, 300),
            'label' => fake()->randomElement(['Essential Reset', 'Signature Journey', 'Royal Sanctuary']),
            'title' => fake()->word() . ' Package',
            'popular' => fake()->boolean(20),
            'description' => fake()->sentence(),
        ];
    }
}
