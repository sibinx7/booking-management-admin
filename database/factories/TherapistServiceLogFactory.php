<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Therapist;
use App\Models\TherapistServiceLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TherapistServiceLog>
 */
class TherapistServiceLogFactory extends Factory
{
    public function definition(): array
    {
        $price = fake()->randomElement([1500.00, 2500.00, 3500.00, 4500.00]);
        $commissionRate = 15.00;
        $commissionAmount = round($price * ($commissionRate / 100), 2);

        return [
            'therapist_id' => Therapist::factory(),
            'service_id' => Service::factory(),
            'service_duration_id' => null,
            'client_id' => null,
            'client_name' => fake()->name(),
            'client_phone' => fake()->phoneNumber(),
            'service_date' => fake()->date(),
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'service_price' => $price,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'tip_amount' => 200.00,
            'status' => 'completed',
            'notes' => 'Therapy session successfully completed',
            'created_by' => User::factory()->admin(),
        ];
    }
}
