<?php

namespace Database\Factories;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentType>
 */
class PaymentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Bank Transfer', 'UPI Payment', 'Cash in Hand', 'Cheque', 'Cryptocurrency']);
        return [
            'name' => $name,
            'code' => fake()->unique()->slug(1),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    /**
     * Bank state
     */
    public function bank(): static
    {
        return $this->state(fn () => [
            'name' => 'Bank Transfer',
            'code' => 'bank',
            'description' => 'Direct NEFT/RTGS/IMPS bank transfer',
            'is_active' => true,
        ]);
    }

    /**
     * UPI state
     */
    public function upi(): static
    {
        return $this->state(fn () => [
            'name' => 'UPI',
            'code' => 'upi',
            'description' => 'Instant Unified Payments Interface (GPay / PhonePe / Paytm)',
            'is_active' => true,
        ]);
    }

    /**
     * Cash state
     */
    public function cash(): static
    {
        return $this->state(fn () => [
            'name' => 'Cash',
            'code' => 'cash',
            'description' => 'Direct cash disbursement at reception/counter',
            'is_active' => true,
        ]);
    }
}
