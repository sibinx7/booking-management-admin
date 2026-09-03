<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $salary_grade_id
 * @property string|null $employee_code
 * @property string $gender
 * @property Carbon|null $dob
 * @property string|null $phone_number
 * @property string|null $profile_pic
 * @property string $role
 * @property string $employment_type
 * @property string $status
 * @property bool $is_active
 * @property Carbon|null $joining_date
 * @property Carbon|null $exit_date
 * @property string|null $exit_reason
 * @property float $base_salary
 * @property string|null $bank_name
 * @property string|null $bank_account_number
 * @property string|null $bank_ifsc
 * @property string|null $upi_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read User $user
 * @property-read SalaryGrade|null $salaryGrade
 * @property-read Therapist|null $therapist
 * @property-read \Illuminate\Database\Eloquent\Collection|SalaryPayment[] $salaryPayments
 * @property-read \Illuminate\Database\Eloquent\Collection|SalaryIncrement[] $salaryIncrements
 */
#[Fillable([
    'user_id',
    'salary_grade_id',
    'employee_code',
    'gender',
    'dob',
    'phone_number',
    'profile_pic',
    'role',
    'employment_type',
    'status',
    'is_active',
    'joining_date',
    'exit_date',
    'exit_reason',
    'base_salary',
    'bank_name',
    'bank_account_number',
    'bank_ifsc',
    'upi_id',
    'notes',
])]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'joining_date' => 'date',
            'exit_date' => 'date',
            'is_active' => 'boolean',
            'base_salary' => 'float',
        ];
    }

    /**
     * Get the employee's name from the associated user account.
     */
    public function getNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    /**
     * Get the employee's email from the associated user account.
     */
    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    /**
     * Calculate and get the employee's age dynamically.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->dob?->age;
    }

    /**
     * Scope a query to only include active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * Scope a query to include inactive employees.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to include terminated employees.
     */
    public function scopeTerminated($query)
    {
        return $query->where('status', 'terminated');
    }

    /**
     * Scope a query to include resigned employees (went to another job/left).
     */
    public function scopeResigned($query)
    {
        return $query->where('status', 'resigned');
    }

    /**
     * Scope a query to include employees on leave.
     */
    public function scopeOnLeave($query)
    {
        return $query->where('status', 'on_leave');
    }

    /**
     * Scope a query by role.
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope a query by employment type.
     */
    public function scopeEmploymentType($query, string $type)
    {
        return $query->where('employment_type', $type);
    }

    /**
     * Scope queries to therapists.
     */
    public function scopeTherapists($query)
    {
        return $query->where('role', 'therapist');
    }

    /**
     * Scope queries to receptionists.
     */
    public function scopeReceptionists($query)
    {
        return $query->where('role', 'receptionist');
    }

    /**
     * Scope queries to cleaners.
     */
    public function scopeCleaners($query)
    {
        return $query->where('role', 'cleaner');
    }

    /**
     * Scope queries to regular employees.
     */
    public function scopeRegular($query)
    {
        return $query->where('employment_type', 'regular');
    }

    /**
     * Scope queries to temporary employees.
     */
    public function scopeTemporary($query)
    {
        return $query->where('employment_type', 'temporary');
    }

    /**
     * Scope queries to guest employees.
     */
    public function scopeGuest($query)
    {
        return $query->where('employment_type', 'guest');
    }

    /**
     * Scope queries by salary grade.
     */
    public function scopeGrade($query, int $salaryGradeId)
    {
        return $query->where('salary_grade_id', $salaryGradeId);
    }

    /**
     * Get the user account associated with the employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the salary grade assigned to the employee.
     */
    public function salaryGrade(): BelongsTo
    {
        return $this->belongsTo(SalaryGrade::class);
    }

    /**
     * Get the therapist profile if this employee is a therapist.
     */
    public function therapist(): HasOne
    {
        return $this->hasOne(Therapist::class);
    }

    /**
     * Get all salary payments/payslips for this employee.
     */
    public function salaryPayments(): HasMany
    {
        return $this->hasMany(SalaryPayment::class);
    }

    /**
     * Get all salary increment records for this employee.
     */
    public function salaryIncrements(): HasMany
    {
        return $this->hasMany(SalaryIncrement::class)->orderBy('effective_date', 'desc');
    }
}
