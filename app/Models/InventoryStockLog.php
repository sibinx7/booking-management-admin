<?php

namespace App\Models;

use Database\Factories\InventoryStockLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $item_id
 * @property string $transaction_type
 * @property float $quantity
 * @property float $unit_cost
 * @property float $total_cost
 * @property Carbon $transaction_date
 * @property string|null $reference_invoice
 * @property string|null $remarks
 * @property int|null $recorded_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read InventoryItem $item
 * @property-read User|null $recorder
 */
#[Fillable([
    'item_id',
    'transaction_type',
    'quantity',
    'unit_cost',
    'total_cost',
    'transaction_date',
    'reference_invoice',
    'remarks',
    'recorded_by',
])]
class InventoryStockLog extends Model
{
    /** @use HasFactory<InventoryStockLogFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
