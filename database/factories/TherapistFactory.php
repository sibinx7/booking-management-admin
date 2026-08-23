<?php

namespace Database\Factories;

use App\Models\Therapist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Therapist>
 */
class TherapistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => fake()->name(),
            'profile_pic' => 'images/therapists/default.jpg',
            'dob' => fake()->dateTimeBetween('-40 years', '-21 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(['female', 'male', 'other']),
            'bio' => fake()->paragraph(),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'education' => ['Certified Massage Therapist Level 2', 'Spa Therapy Advanced Course'],
            'is_active' => true,
            'is_online' => fake()->boolean(50),
            'commission_rate' => fake()->randomElement([40.00, 50.00, 60.00]),
            'payment_info' => 'Bank Transfer - ' . fake()->bankAccountNumber(),
            'rating' => fake()->randomFloat(2, 4.5, 5.0),
            'review_count' => fake()->numberBetween(5, 45),
        ];
    }
}
