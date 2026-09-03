<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceReview>
 */
class ServiceReviewFactory extends Factory
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
            'author_name' => fake()->name(),
            'rating' => fake()->numberBetween(4, 5),
            'date' => fake()->date(),
            'comment' => fake()->paragraph(),
            'treatment_duration' => fake()->randomElement(['60 mins', '90 mins', '120 mins']),
            'verified_guest' => true,
            'is_published' => true,
            'published_at' => now(),
        ];
    }

    /**
     * Indicate that the review is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
