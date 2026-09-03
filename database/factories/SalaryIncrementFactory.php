<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\SalaryGrade;
use App\Models\SalaryIncrement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalaryIncrement>
 */
class SalaryIncrementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $previousSalary = fake()->randomElement([20000.00, 25000.00, 30000.00, 35000.00]);
        $incrementAmount = fake()->randomElement([3000.00, 4000.00, 5000.00, 7500.00]);
        $newSalary = $previousSalary + $incrementAmount;
        $percentage = round(($incrementAmount / $previousSalary) * 100, 2);

        return [
            'employee_id' => Employee::factory(),
            'salary_grade_id' => SalaryGrade::factory(),
            'previous_salary' => $previousSalary,
            'increment_amount' => $incrementAmount,
            'new_salary' => $newSalary,
            'increment_percentage' => $percentage,
            'effective_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'reason' => fake()->randomElement([
                'Annual Performance Appraisal',
                'Promotion to Senior Grade',
                'Exemplary Client Satisfaction Feedback',
                'Cost of Living & Market Adjustment',
            ]),
            'approved_by' => User::factory()->admin(),
            'remarks' => fake()->sentence(),
        ];
    }
}
