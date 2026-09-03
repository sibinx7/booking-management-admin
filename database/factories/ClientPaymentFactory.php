<?php

namespace Database\Factories;

use App\Models\ClientPayment;
use App\Models\Receptionist;
use App\Models\Service;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClientPayment>
 */
class ClientPaymentFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = 3500.00;
        $tax = 175.00;
        $total = $subtotal + $tax;

        return [
            'invoice_number' => 'INV-' . date('Ymd') . '-' . fake()->unique()->numerify('####'),
            'therapist_service_log_id' => null,
            'therapist_id' => Therapist::factory(),
            'receptionist_id' => Receptionist::factory(),
            'service_id' => Service::factory(),
            'client_id' => null,
            'client_name' => fake()->name(),
            'client_phone' => fake()->phoneNumber(),
            'subtotal' => $subtotal,
            'discount_amount' => 0.00,
            'tax_amount' => $tax,
            'total_amount' => $total,
            'payment_method' => 'upi',
            'upi_transaction_id' => 'UPI-' . fake()->numerify('############'),
            'upi_app' => 'Google Pay',
            'card_transaction_id' => null,
            'card_last_four' => null,
            'card_network' => null,
            'cash_receipt_number' => null,
            'cash_denomination_details' => null,
            'receipt_image_path' => 'receipts/sample-upi-proof.jpg',
            'payment_date' => now(),
            'payment_status' => 'completed',
            'notes' => 'Full payment settled at reception desk',
            'received_by' => User::factory()->admin(),
        ];
    }

    public function cash(): static
    {
        return $this->state(fn () => [
            'payment_method' => 'cash',
            'upi_transaction_id' => null,
            'upi_app' => null,
            'cash_receipt_number' => 'REC-' . fake()->numerify('######'),
            'cash_denomination_details' => ['500' => 7, '200' => 0, '100' => 1],
        ]);
    }

    public function card(): static
    {
        return $this->state(fn () => [
            'payment_method' => 'card',
            'upi_transaction_id' => null,
            'upi_app' => null,
            'card_transaction_id' => 'POS-TXN-' . fake()->numerify('########'),
            'card_last_four' => '4242',
            'card_network' => 'Visa',
        ]);
    }
}
