<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\SalaryGrade;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->employee(),
            'salary_grade_id' => null,
            'employee_code' => 'EMP-' . fake()->unique()->numerify('###'),
            'gender' => fake()->randomElement(['female', 'male', 'other']),
            'dob' => fake()->dateTimeBetween('-45 years', '-20 years')->format('Y-m-d'),
            'phone_number' => fake()->phoneNumber(),
            'profile_pic' => 'images/employees/default.jpg',
            'role' => fake()->randomElement(['therapist', 'receptionist', 'cleaner', 'laundry', 'manager']),
            'employment_type' => fake()->randomElement(['regular', 'temporary', 'guest']),
            'status' => 'active',
            'is_active' => true,
            'joining_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'exit_date' => null,
            'exit_reason' => null,
            'base_salary' => fake()->randomElement([15000.00, 20000.00, 25000.00, 35000.00, 50000.00]),
            'bank_name' => 'HDFC Bank',
            'bank_account_number' => fake()->numerify('##############'),
            'bank_ifsc' => 'HDFC0001234',
            'upi_id' => fake()->userName() . '@okaxis',
            'notes' => fake()->sentence(),
        ];
    }

    /**
     * Therapist role
     */
    public function therapist(): static
    {
        return $this->state(fn () => [
            'role' => 'therapist',
        ]);
    }

    /**
     * Receptionist role
     */
    public function receptionist(): static
    {
        return $this->state(fn () => [
            'role' => 'receptionist',
            'base_salary' => 22000.00,
        ]);
    }

    /**
     * Cleaner role
     */
    public function cleaner(): static
    {
        return $this->state(fn () => [
            'role' => 'cleaner',
            'base_salary' => 14000.00,
        ]);
    }

    /**
     * Laundry role
     */
    public function laundry(): static
    {
        return $this->state(fn () => [
            'role' => 'laundry',
            'base_salary' => 15000.00,
        ]);
    }

    /**
     * Regular employment type
     */
    public function regular(): static
    {
        return $this->state(fn () => [
            'employment_type' => 'regular',
        ]);
    }

    /**
     * Temporary employment type
     */
    public function temporary(): static
    {
        return $this->state(fn () => [
            'employment_type' => 'temporary',
        ]);
    }

    /**
     * Guest employment type
     */
    public function guest(): static
    {
        return $this->state(fn () => [
            'employment_type' => 'guest',
        ]);
    }

    /**
     * Active employee status
     */
    public function active(): static
    {
        return $this->state(fn () => [
            'status' => 'active',
            'is_active' => true,
            'exit_date' => null,
            'exit_reason' => null,
        ]);
    }

    /**
     * Terminated employee status
     */
    public function terminated(): static
    {
        return $this->state(fn () => [
            'status' => 'terminated',
            'is_active' => false,
            'exit_date' => now()->format('Y-m-d'),
            'exit_reason' => 'Terminated for contract breach/disciplinary reasons',
        ]);
    }

    /**
     * Resigned employee status (left for another job / personal reasons)
     */
    public function resigned(): static
    {
        return $this->state(fn () => [
            'status' => 'resigned',
            'is_active' => false,
            'exit_date' => now()->format('Y-m-d'),
            'exit_reason' => 'Resigned to pursue another job opportunity',
        ]);
    }

    /**
     * On leave status
     */
    public function onLeave(): static
    {
        return $this->state(fn () => [
            'status' => 'on_leave',
            'is_active' => true,
        ]);
    }
}
