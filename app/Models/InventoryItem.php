<?php

namespace App\Models;

use Database\Factories\InventoryItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $sku
 * @property string $unit
 * @property float $current_stock
 * @property float $reorder_threshold
 * @property float $unit_cost
 * @property string|null $supplier_name
 * @property string|null $notes
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read InventoryCategory $category
 * @property-read InventoryStockLog[] $stockLogs
 */
#[Fillable([
    'category_id',
    'name',
    'sku',
    'unit',
    'current_stock',
    'reorder_threshold',
    'unit_cost',
    'supplier_name',
    'notes',
    'is_active',
])]
class InventoryItem extends Model
{
    /** @use HasFactory<InventoryItemFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'current_stock' => 'decimal:2',
            'reorder_threshold' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(InventoryStockLog::class, 'item_id');
    }

    public function scopeLowStock(Builder $query): void
    {
        $query->whereColumn('current_stock', '<=', 'reorder_threshold');
    }
}
