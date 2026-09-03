<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomType>
 */
class RoomTypeFactory extends Factory
{
    public function definition(): array
    {
        $code = fake()->unique()->randomElement(['single', 'couple', 'vip', 'hydrotherapy_suite', 'honeymoon']);
        $name = ucwords(str_replace('_', ' ', $code)) . ' Suite';

        return [
            'name' => $name,
            'code' => $code,
            'description' => fake()->sentence(),
            'featured_image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
            'is_active' => true,
        ];
    }
}
