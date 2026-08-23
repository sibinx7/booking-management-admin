<?php

namespace Database\Factories;

use App\Models\Duration;
use App\Models\Service;
use App\Models\ServiceSpecialOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceSpecialOffer>
 */
class ServiceSpecialOfferFactory extends Factory
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
            'duration_id' => null,
            'badge' => fake()->randomElement(['Seasonal Special', 'Weekend Bliss', 'Members Only']),
            'title' => fake()->sentence(3),
            'discount' => fake()->randomElement(['15% OFF', '20% OFF', '$30 Discount']),
            'description' => fake()->sentence(),
            'promo_code' => fake()->bothify('????##'),
            'is_active' => true,
            'start_at' => null,
            'end_at' => null,
        ];
    }
}
