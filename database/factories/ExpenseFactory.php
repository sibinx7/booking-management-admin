<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'expense_category_id' => ExpenseCategory::factory(),
            'title' => fake()->sentence(3),
            'amount' => fake()->randomFloat(2, 500, 15000),
            'expense_date' => fake()->date(),
            'due_date' => fake()->date(),
            'paid_date' => fake()->date(),
            'payment_method' => 'bank_transfer',
            'payment_reference_no' => 'TXN-' . fake()->numerify('##########'),
            'vendor_name' => fake()->company(),
            'bill_invoice_number' => 'BILL-' . fake()->numerify('#####'),
            'receipt_image_path' => 'expenses/sample-bill.jpg',
            'status' => 'paid',
            'notes' => 'Utility bill paid on time',
            'created_by' => User::factory()->admin(),
        ];
    }
}
