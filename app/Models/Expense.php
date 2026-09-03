<?php

namespace App\Models;

use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $expense_category_id
 * @property string $title
 * @property float $amount
 * @property Carbon $expense_date
 * @property Carbon|null $due_date
 * @property Carbon|null $paid_date
 * @property string $payment_method
 * @property string|null $payment_reference_no
 * @property string|null $vendor_name
 * @property string|null $bill_invoice_number
 * @property string|null $receipt_image_path
 * @property string $status
 * @property string|null $notes
 * @property int|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ExpenseCategory $category
 * @property-read User|null $creator
 */
#[Fillable([
    'expense_category_id',
    'title',
    'amount',
    'expense_date',
    'due_date',
    'paid_date',
    'payment_method',
    'payment_reference_no',
    'vendor_name',
    'bill_invoice_number',
    'receipt_image_path',
    'status',
    'notes',
    'created_by',
])]
class Expense extends Model
{
    /** @use HasFactory<ExpenseFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePaid(Builder $query): void
    {
        $query->where('status', 'paid');
    }

    public function scopeForMonth(Builder $query, int $year, int $month): void
    {
        $query->whereYear('expense_date', $year)->whereMonth('expense_date', $month);
    }
}
