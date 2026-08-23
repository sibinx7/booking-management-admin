<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceHighlight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceHighlight>
 */
class ServiceHighlightFactory extends Factory
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
            'icon' => fake()->randomElement(['bi-flower1', 'bi-droplet', 'bi-wind']),
            'title' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'image' => null,
        ];
    }
}
