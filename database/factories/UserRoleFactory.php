<?php

namespace Database\Factories;

use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserRole>
 */
class UserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = fake()->unique()->randomElement(['Client', 'Employee', 'Admin', 'Owner', 'Manager', 'Accountant']);
        return [
            'name' => $role,
            'code' => fake()->unique()->slug(1),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    /**
     * Client role
     */
    public function client(): static
    {
        return $this->state(fn () => [
            'name' => 'Client',
            'code' => 'client',
            'description' => 'Standard client and customer profile',
            'is_active' => true,
        ]);
    }

    /**
     * Employee role
     */
    public function employee(): static
    {
        return $this->state(fn () => [
            'name' => 'Employee',
            'code' => 'employee',
            'description' => 'Internal spa staff member (therapist, receptionist, cleaner, laundry)',
            'is_active' => true,
        ]);
    }

    /**
     * Admin role
     */
    public function admin(): static
    {
        return $this->state(fn () => [
            'name' => 'Admin',
            'code' => 'admin',
            'description' => 'System administrator or spa manager',
            'is_active' => true,
        ]);
    }
}
