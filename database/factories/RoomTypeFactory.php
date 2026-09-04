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
        $name = ucwords(fake()->words(2, true)) . ' Suite';
        $code = fake()->unique()->slug(2);

        return [
            'name' => $name,
            'code' => $code,
            'description' => fake()->sentence(),
            'featured_image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
            'is_active' => true,
        ];
    }
}
