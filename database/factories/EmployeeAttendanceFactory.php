<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmployeeAttendance>
 */
class EmployeeAttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'therapist_id' => null,
            'date' => fake()->date(),
            'status' => 'present',
            'shift_type' => 'general_full_day',
            'check_in_time' => '08:00:00',
            'check_out_time' => '17:00:00',
            'work_hours' => 8.00,
            'notes' => 'Regular daily check-in',
            'recorded_by' => User::factory()->admin(),
        ];
    }

    public function morning(): static
    {
        return $this->state(fn () => [
            'status' => 'half_day',
            'shift_type' => 'morning_shift',
            'check_in_time' => '08:00:00',
            'check_out_time' => '13:00:00',
            'work_hours' => 5.00,
        ]);
    }

    public function evening(): static
    {
        return $this->state(fn () => [
            'status' => 'half_day',
            'shift_type' => 'evening_shift',
            'check_in_time' => '13:00:00',
            'check_out_time' => '20:00:00',
            'work_hours' => 7.00,
        ]);
    }

    public function forTherapist(Therapist $therapist): static
    {
        return $this->state(fn () => [
            'employee_id' => $therapist->employee_id,
            'therapist_id' => $therapist->id,
        ]);
    }
}
