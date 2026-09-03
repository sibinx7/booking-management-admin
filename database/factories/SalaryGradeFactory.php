<?php

namespace Database\Factories;

use App\Models\SalaryGrade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalaryGrade>
 */
class SalaryGradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gradeName = fake()->unique()->randomElement([
            'Grade A - Senior Specialist',
            'Grade B - Standard Professional',
            'Grade C - Junior Associate',
            'Grade D - Support Staff',
            'Grade M - Management Lead',
        ]);

        return [
            'name' => $gradeName,
            'code' => 'GRADE-' . fake()->unique()->lexify('???'),
            'min_salary' => 15000.00,
            'max_salary' => 50000.00,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
