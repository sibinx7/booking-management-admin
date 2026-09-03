<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_type_id' => RoomType::factory(),
            'room_number' => 'Suite ' . fake()->unique()->numberBetween(101, 199),
            'name' => fake()->randomElement(['Lotus Sanctuary', 'Mahogany Haven', 'Ayurvedic Retreat', 'Aroma Oasis']),
            'bed_count' => 1,
            'height_feet' => 11.50,
            'length_feet' => 18.00,
            'width_feet' => 14.00,
            'area_sqft' => 252.00,
            'featured_image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
            'gallery_images' => [
                'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&w=1200&q=80',
            ],
            'is_highlighted' => false,
            'highlight_tag' => null,
            'has_jacuzzi' => fake()->boolean(60),
            'has_sauna' => fake()->boolean(40),
            'has_steam_bath' => fake()->boolean(70),
            'has_shower' => true,
            'has_toilet' => true,
            'has_ac' => true,
            'has_candle_light' => fake()->boolean(40),
            'has_two_massage_beds' => false,
            'extra_amenities' => ['Aromatherapy Diffuser', 'Bose Ambient Music'],
            'status' => 'available',
            'is_active' => true,
            'description' => 'Luxury spa treatment suite with private amenities',
        ];
    }

    public function highlighted(): static
    {
        return $this->state(fn () => [
            'is_highlighted' => true,
            'highlight_tag' => 'Signature Sanctuary',
        ]);
    }

    public function coupleSuite(): static
    {
        return $this->state(fn () => [
            'room_type_id' => RoomType::firstOrCreate(['code' => 'couple'], ['name' => 'Couple Suite', 'description' => 'Suite for couples']),
            'name' => 'Royal Couple Mahogany Suite',
            'bed_count' => 2,
            'height_feet' => 12.00,
            'length_feet' => 22.00,
            'width_feet' => 16.00,
            'area_sqft' => 352.00,
            'is_highlighted' => true,
            'highlight_tag' => 'Most Popular for Couples',
            'has_two_massage_beds' => true,
            'has_jacuzzi' => true,
            'has_sauna' => true,
            'has_steam_bath' => true,
            'has_candle_light' => true,
            'extra_amenities' => ['Dual Headrest Beds', 'Rose Petal Jacuzzi', 'Champagne Cooler', 'Candle Wall Sconces'],
        ]);
    }
}
