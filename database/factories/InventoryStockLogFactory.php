<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\InventoryStockLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryStockLog>
 */
class InventoryStockLogFactory extends Factory
{
    public function definition(): array
    {
        $qty = 10.00;
        $unitCost = 250.00;

        return [
            'item_id' => InventoryItem::factory(),
            'transaction_type' => 'purchase_in',
            'quantity' => $qty,
            'unit_cost' => $unitCost,
            'total_cost' => $qty * $unitCost,
            'transaction_date' => fake()->date(),
            'reference_invoice' => 'BILL-' . fake()->numerify('#####'),
            'remarks' => 'Inventory restock batch received',
            'recorded_by' => User::factory()->admin(),
        ];
    }
}
