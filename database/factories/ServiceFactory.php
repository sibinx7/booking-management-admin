<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);
        return [
            'slug' => Str::slug($title),
            'title' => ucwords($title),
            'tagline' => fake()->sentence(),
            'category' => fake()->randomElement(['Couples & Romantic', 'Exotic Massage', 'Holistic Healing', 'Hydrotherapy']),
            'hero_image' => 'images/treatments/default.jpg',
            'images' => json_encode(['images/treatments/gallery1.jpg', 'images/treatments/gallery2.jpg']),
            'is_new' => fake()->boolean(30),
            'is_unlimited' => true,
            'start' => null,
            'end' => null,
            'is_discount_active' => false,
            'discount_type' => 'percentage',
            'discount_value' => 0.00,
            'discount_start_at' => null,
            'discount_end_at' => null,
            'rating' => fake()->randomFloat(2, 4, 5),
            'review_count' => fake()->numberBetween(10, 100),
            'overview' => fake()->paragraph(),
            'full_description' => fake()->paragraphs(3, true),
            'ritual_steps' => json_encode(['Welcome Foot Ritual', 'Therapeutic Treatment', 'Post-Ritual Tea & Relax']),
        ];
    }
}
