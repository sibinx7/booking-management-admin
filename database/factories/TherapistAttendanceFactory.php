<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\Therapist;
use App\Models\TherapistAttendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TherapistAttendance>
 */
class TherapistAttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'therapist_id' => Therapist::factory(),
            'employee_id' => Employee::factory()->therapist(),
            'employee_attendance_id' => null,
            'date' => fake()->date(),
            'shift_type' => 'full_day',
            'duty_start_time' => '09:00:00',
            'duty_end_time' => '18:00:00',
            'room_id' => \App\Models\Room::factory(),
            'status' => 'on_duty',
            'max_sessions_allowed' => 6,
            'remarks' => 'Assigned to treatment suite for full day',
            'allocated_by' => User::factory()->admin(),
        ];
    }

    public function morning(): static
    {
        return $this->state(fn () => [
            'shift_type' => 'morning_shift',
            'duty_start_time' => '08:00:00',
            'duty_end_time' => '14:00:00',
            'max_sessions_allowed' => 4,
        ]);
    }

    public function evening(): static
    {
        return $this->state(fn () => [
            'shift_type' => 'evening_shift',
            'duty_start_time' => '14:00:00',
            'duty_end_time' => '20:00:00',
            'max_sessions_allowed' => 4,
        ]);
    }
}
