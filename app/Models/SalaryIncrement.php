<?php

namespace App\Models;

use Database\Factories\SalaryIncrementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employee_id
 * @property int|null $salary_grade_id
 * @property float $previous_salary
 * @property float $increment_amount
 * @property float $new_salary
 * @property float|null $increment_percentage
 * @property Carbon $effective_date
 * @property string|null $reason
 * @property int|null $approved_by
 * @property string|null $remarks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Employee $employee
 * @property-read SalaryGrade|null $salaryGrade
 * @property-read User|null $approver
 */
#[Fillable([
    'employee_id',
    'salary_grade_id',
    'previous_salary',
    'increment_amount',
    'new_salary',
    'increment_percentage',
    'effective_date',
    'reason',
    'approved_by',
    'remarks',
])]
class SalaryIncrement extends Model
{
    /** @use HasFactory<SalaryIncrementFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'previous_salary' => 'float',
            'increment_amount' => 'float',
            'new_salary' => 'float',
            'increment_percentage' => 'float',
            'effective_date' => 'date',
        ];
    }

    /**
     * Get the employee who received this increment.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the promoted/applicable salary grade.
     */
    public function salaryGrade(): BelongsTo
    {
        return $this->belongsTo(SalaryGrade::class);
    }

    /**
     * Get the admin user who approved this increment.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
