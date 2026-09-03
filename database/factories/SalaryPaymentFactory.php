<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\PaymentType;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalaryPayment>
 */
class SalaryPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = 2026;
        $month = fake()->numberBetween(1, 12);
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        $paymentDate = date('Y-m-01', strtotime($startDate . ' + 1 month'));

        return [
            'employee_id' => Employee::factory(),
            'payment_type_id' => PaymentType::factory()->bank(),
            'payslip_number' => sprintf('PAY-%d-%02d-%s', $year, $month, fake()->unique()->numerify('####')),
            'month' => $month,
            'year' => $year,
            'period_start_date' => $startDate,
            'period_end_date' => $endDate,
            'amount' => fake()->randomFloat(2, 12000, 45000),
            'payment_date' => $paymentDate,
            'deposited_date' => $paymentDate,
            'status' => 'deposited',
            'reference_number' => 'TXN-' . fake()->numerify('##########'),
            'remarks' => 'Monthly regular salary payslip payout',
            'created_by' => User::factory()->admin(),
        ];
    }

    /**
     * Pending payment state
     */
    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
            'deposited_date' => null,
            'reference_number' => null,
        ]);
    }

    /**
     * Deposited payment state
     */
    public function deposited(): static
    {
        return $this->state(fn () => [
            'status' => 'deposited',
            'deposited_date' => now()->format('Y-m-d'),
        ]);
    }
}
