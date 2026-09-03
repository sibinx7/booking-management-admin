<?php

namespace App\Models;

use Database\Factories\SalaryPaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $payment_type_id
 * @property string|null $payslip_number
 * @property int $month
 * @property int $year
 * @property Carbon $period_start_date
 * @property Carbon $period_end_date
 * @property int $total_working_days
 * @property int $present_days
 * @property int $absent_days
 * @property int $leave_days
 * @property float $base_salary_amount
 * @property float $attendance_adjusted_base
 * @property int $services_completed_count
 * @property float $service_commission_amount
 * @property float $bonus_amount
 * @property float $deduction_amount
 * @property float $amount
 * @property Carbon $payment_date
 * @property Carbon|null $deposited_date
 * @property string $status
 * @property string|null $reference_number
 * @property string|null $remarks
 * @property int|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Employee $employee
 * @property-read PaymentType $paymentType
 * @property-read User|null $creator
 */
#[Fillable([
    'employee_id',
    'payment_type_id',
    'payslip_number',
    'month',
    'year',
    'period_start_date',
    'period_end_date',
    'total_working_days',
    'present_days',
    'absent_days',
    'leave_days',
    'base_salary_amount',
    'attendance_adjusted_base',
    'services_completed_count',
    'service_commission_amount',
    'bonus_amount',
    'deduction_amount',
    'amount',
    'payment_date',
    'deposited_date',
    'status',
    'reference_number',
    'remarks',
    'created_by',
])]
class SalaryPayment extends Model
{
    /** @use HasFactory<SalaryPaymentFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'month' => 'integer',
            'year' => 'integer',
            'period_start_date' => 'date',
            'period_end_date' => 'date',
            'total_working_days' => 'integer',
            'present_days' => 'integer',
            'absent_days' => 'integer',
            'leave_days' => 'integer',
            'base_salary_amount' => 'decimal:2',
            'attendance_adjusted_base' => 'decimal:2',
            'services_completed_count' => 'integer',
            'service_commission_amount' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'deduction_amount' => 'decimal:2',
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'deposited_date' => 'date',
        ];
    }

    /**
     * Get the readable English month name (e.g. "August").
     */
    public function getMonthNameAttribute(): string
    {
        return Carbon::createFromDate($this->year, $this->month, 1)->format('F');
    }

    /**
     * Scope a query to only include deposited payments.
     */
    public function scopeDeposited($query)
    {
        return $query->where('status', 'deposited');
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query by month and year.
     */
    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    /**
     * Scope a query by date range.
     */
    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('payment_date', [$from, $to]);
    }

    /**
     * Get the employee associated with the payment.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the payment type (Bank, UPI, Cash) used.
     */
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    /**
     * Get the admin/user who created this payment record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
