<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'country_code' => '+91',
            'phone_number' => fake()->unique()->numerify('##########'),
            'registration_mode' => 'both',
            'google_id' => null,
            'phone_verified_at' => now(),
            'email_otp' => null,
            'email_otp_expires_at' => null,
            'phone_otp' => null,
            'phone_otp_expires_at' => null,
        ];
    }
}
