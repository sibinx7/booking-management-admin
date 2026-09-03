<?php

namespace Database\Factories;

use App\Models\RoomHighlight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomHighlight>
 */
class RoomHighlightFactory extends Factory
{
    public function definition(): array
    {
        $code = fake()->unique()->slug(2);

        return [
            'name' => ucwords(fake()->words(3, true)),
            'code' => $code,
            'icon' => fake()->randomElement(['sparkles', 'heart', 'bath', 'flame', 'music', 'bed', 'shower-head']),
            'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80',
            'category' => fake()->randomElement(['ambience', 'sensual_rituals', 'amenities', 'wellness', 'privacy']),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
